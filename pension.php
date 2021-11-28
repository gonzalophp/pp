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

$markets = ['investment_rate', 'pension_rate'];

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
        $selected = (isset($formData[$market]) && ($formData[$market] == $csv)) ? ' selected' : '';
        if ($selected) {
            $marketRates[$market] = new MarketRate($csv);
        }
        $options .= "<option$selected value=\"$csv\">$csv</option>";
    }
    $formData[$market] = $options;
}


$priceGenerator = new PriceGenerator();


$ratesInvestment = array_map(
    fn() => $marketRates['investment_rate']->getRandomRate(), 
    range(1, 12 * $formData['years'])
);

$pricesInvestment = $priceGenerator->getPrices(
        $formData['investment_amount'],
        $ratesInvestment,
        $formData['investment_monthly_contribution']
);

$ratesPension = array_map(
        fn() => $marketRates['pension_rate']->getRandomRate(),
        range(1, 12 * $formData['years'])
);

$pricesPension = $priceGenerator->getPrices(
        $formData['pension_amount'],
        $ratesPension,
        $formData['pension_monthly_contribution']
);


$chart = new Chart(900, 600);
$formData['chart'] = 'data:image/png;base64,' . 
        $chart->getImageDataBase64($pricesInvestment, $pricesPension);

$template = new Template();
$renderedHtml = $template->render('view.html', $formData);

echo $renderedHtml;
