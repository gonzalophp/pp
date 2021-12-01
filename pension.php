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


$priceGenerator = new PriceGenerator();
$sumOfMarketPrices = $priceGenerator->getSumOfPrices(
        $marketRates, 
        $formData['years'] * 12,
        $formData
        );


$chart = new Chart(900, 600);
$formData['chart'] = 'data:image/png;base64,' . 
        $chart->getImageDataBase64($sumOfMarketPrices);

$template = new Template();
echo $template->render('view.html', $formData);
