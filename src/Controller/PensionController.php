<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Cookie;
use App\Entity\Chart;
use App\Entity\PriceGenerator;
use App\Entity\MarketRate;


class PensionController extends AbstractController
{

    #[Route('/pension', name: 'pension_index')]
    public function index(): Response
    {
//        return new Response('aaaaaaaaaaaaa');
        
        return $this->render('@chart/view.html.twig', $this->getFormData());
    }
    
    private function getFormData(): array
    {
        $formData = [
            'years' => 0,
            'investment_amount' => 0,
            'investment_rate' => 0,
            'investment_contribution_years' => 0,
            'pension_rate' => 0,
            'pension_amount' => 0,
            'pension_contribution_years' => 0,
            'simulations' => 0,
            'remove_percentile' => 0,
            'investment_monthly_contribution' => 0,
            'pension_monthly_contribution' => 0,
            'withdraw_amount' => 0,
            'withdraw_year' => 0
        ];
        $cookiePensionPost = new Cookie('pension_data');
        if (!empty($_POST)) {
            $formData = array_merge($formData, $_POST);
            $cookiePensionPost->store($formData);
        } else {
            $formData = $cookiePensionPost->getData() ?? $formData;
        }
        
        $formData['chart'] = $this->getChart($formData);
        
        return $formData;
    }
    
    private function getChart($formData): string
    {
        $filtered = array_filter(
                scandir('.'),
                fn($v) => (substr($v, -3) == 'csv')
        );

        $marketRates = [];
        $markets = ['investment', 'pension'];
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
        $simulations = (int) ($formData['simulations'] / (1 - (($percentile * 2) / 100)));
        $simulations = (($simulations % 2) == 0) ? $simulations : ++$simulations;

        var_dump(['$simulations' => $simulations]);
        
        $priceGenerator = new PriceGenerator();
        $sumOfMarketPrices = [];
        if ($simulations > 0) {
            foreach (range(1, $simulations) as $v) {
                var_dump(['$v' => $v]);
                $sumOfMarketPrices[] = $priceGenerator->getSumOfPrices(
                        $marketRates,
                        $formData['years'] * 12,
                        $formData
                );
            }
        }
        
        var_dump(['$sumOfMarketPrices'=> $sumOfMarketPrices]);
        
        $chart = new Chart(900, 600);
        $base64Image = $chart->getImageDataBase64(...$sumOfMarketPrices);
        
        if (count($sumOfMarketPrices) > 0) {
            $finalValues = array_column($sumOfMarketPrices, array_key_last(current($sumOfMarketPrices)));
            echo "Avg final price: " . number_format(array_sum($finalValues) / count($finalValues));
            echo "<br/>";

            $finalValueNegative = array_reduce($sumOfMarketPrices, function ($carry, $item) {
                return (end($item) < 0) ? ++$carry : $carry;
            }, 0);
            echo "Probability of success: " . number_format(((count($finalValues) - $finalValueNegative) / count($finalValues)) * 100) . "%";
            echo "<br/>";
        }
            

        return 'data:image/png;base64,' . $base64Image;
    }
}
