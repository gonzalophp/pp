<?php
// phpinfo();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('cookie.php');
require_once('chart.php');
require_once('template.php');
require_once('marketRate.php');
require_once('priceGenerator.php');

$markets = ['investment', 'pension'];

$cookiePensionPost = new Cookie('pension_data');

if (!empty($_POST)) {
    $formData = $_POST;
    $cookiePensionPost->store($formData);
} else {
    $formData = $cookiePensionPost->getData() ?? [];
}


$filtered = array_filter(
    scandir('.'),
    fn($v) => (substr($v, -3) == 'csv')
);

foreach ($markets as $market) {
    $options = '';
    foreach ($filtered as $csv) {
        $selected = (isset($formData[$market . '_rate']) && ($formData[$market . '_rate'] == $csv)) ? ' selected' : '';
        if ($selected) {
            $marketRates[$market] = new MarketRate($csv);
        }
        $options .= "<option$selected value=\"$csv\">$csv</option>";
    }
    $formData[$market . '_rate'] = $options;
}


$percentile = (int) $formData['remove_percentile'];
$simulations = (int) ($formData['simulations'] / (1-(($percentile * 2)/100)));
$simulations = (($simulations % 2) == 0) ? $simulations : ++$simulations;


$priceGenerator = new PriceGenerator();
$sumOfMarketPrices = [];
foreach (range(1, $simulations) as $v) {
    $sumOfMarketPrices[] = $priceGenerator->getSumOfPrices(
            $marketRates,
            $formData['years'] * 12,
            $formData
    );
}

$lastValues = array_column($sumOfMarketPrices, array_key_last(current($sumOfMarketPrices)));
asort($lastValues, SORT_NUMERIC);
$lastValues = array_slice($lastValues, ($simulations-$formData['simulations'])/2, $formData['simulations']);



$chart = new Chart(900, 600);
$base64Image = $chart->getImageDataBase64(...$sumOfMarketPrices);

$formData['chart'] = 'data:image/png;base64,' . $base64Image;

$template = new Template();
echo $template->render('view.html', $formData);


$finalValues = array_column($sumOfMarketPrices, array_key_last(current($sumOfMarketPrices)));
echo "Avg final price: " . number_format(array_sum($finalValues)/count($finalValues));
echo "<br/>";

$finalValueNegative = array_reduce($sumOfMarketPrices, function($carry, $item) {return (end($item) < 0) ? ++$carry: $carry; }, 0);
echo "Probability of success: " . number_format(((count($finalValues) -$finalValueNegative) / count($finalValues)) * 100) . "%";
echo "<br/>";
