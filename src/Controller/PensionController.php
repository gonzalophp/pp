<?php
namespace App\Controller;

use App\Helper\FileSearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Cookie;
use App\Service\Chart;
use App\Entity\PriceGenerator;
use App\Repository\MarketGrowthRate\Adapter\AdapterFactory;
use App\Repository\MarketGrowthRateRepository;

use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

class PensionController extends AbstractController
{
    private const COOKIE_NAME = 'pension_data';

    public function __construct(
        private FileSearch $fileSearch,
        private AdapterFactory $adapterFactory,
        private Chart $chart
        )
    {

    }
    
    #[Route('/pension', name: 'pension_index')]
    public function index(): Response
    {

        return $this->render(
            '@chart/view.html.twig', 
            $this->getFormData()
        );
    }
    
    private function getFormData(): array
    {
        $formData = $this->getFormDataMergedWithCookieData();

        $csvFiles = $this->fileSearch->getRecursive(
            $this->getParameter('resources')['market_prices']['path'],
            '*.csv'
        );

        $csvFiles = iterator_to_array($csvFiles);

        $markets = ['investment', 'pension'];
        foreach ($markets as $market) {
            $options = [];
            foreach ($csvFiles as $csvFile) {
                $selected = (isset($formData[$market . '_rate']) && ($formData[$market . '_rate'] == $csvFile));
                $options[] = [
                    'name' => $csvFile, 
                    'selected' => $selected
                ];
            }
            $formData[$market . '_rate_options'] = $options;
        }
        
        $marketGrowthRatesRepositories = $this->getMarketGrowthRepositories($markets, $formData);
        $sumOfMarketPrices = $this->getSumOfMarketPrices(
            $marketGrowthRatesRepositories, 
            $formData
        );
        $formData['chart'] = 'data:image/png;base64,' . $this->chart->getImageDataBase64(900, 600, $sumOfMarketPrices);

        if (count($sumOfMarketPrices) > 0) {
            $finalValues = array_column($sumOfMarketPrices, array_key_last(current($sumOfMarketPrices)));
            $initialValue = $sumOfMarketPrices[0][0];
            $averageFinalValue = array_sum($finalValues) / count($finalValues);           
            
            
            $periods = (count($sumOfMarketPrices[0]) - 1);
            $interest = 12 * (pow(($averageFinalValue/$initialValue), (1/$periods)) - 1);
            $interestRate = number_format(($interest * 100), 2);

            $finalValueNegativeCount = array_reduce($sumOfMarketPrices, function ($carry, $item) {
                return (end($item) < 0) ? ++$carry : $carry;
            }, 0);

            $statsProbabilitySuccess = number_format(((count($finalValues) - $finalValueNegativeCount) / count($finalValues)) * 100, 2);
            
            $formData['stats_amount_start'] = $sumOfMarketPrices[0][0];
            $formData['stats_amount_end'] = $averageFinalValue;
            $formData['stats_periods'] = $periods;
            $formData['stats_rate'] = $interestRate;
            $formData['stats_probability_success'] = $statsProbabilitySuccess;
        }
        
        return $formData;
    }

    private function getMarketGrowthRepositories(array $markets, array $formData): array {
        $marketGrowthRatesRepositories = [];
        foreach ($markets as $market) {
            if (isset($formData[$market . '_rate'])) {
                $marketRateGrowthAdapter = $this->adapterFactory
                    ->getAdapter($formData[$market . '_rate']);
                $marketGrowthRatesRepositories[$market] = new MarketGrowthRateRepository($marketRateGrowthAdapter);
            }
        }

        return $marketGrowthRatesRepositories;
    }
    
    private function getSumOfMarketPrices(array $marketGrowthRatesRepositories, $formData): array
    {   
        $sumOfMarketPrices = [];
        
        if ($marketGrowthRatesRepositories) {
            $percentile = (int) $formData['remove_percentile'];
            $simulations = (int) ($formData['simulations'] / (1 - (($percentile * 2) / 100)));
            $simulations = (($simulations % 2) == 0) ? $simulations : ++$simulations;

            $priceGenerator = new PriceGenerator();
            
            if ($simulations > 0) {
                foreach (range(1, $simulations) as $v) {
                    $sumOfMarketPrices[] = $priceGenerator->getSumOfPrices(
                            $marketGrowthRatesRepositories,
                            $formData['years'] * 12,
                            $formData
                    );
                }
            }
                    
            $sumOfMarketPrices = $this->filterPercentile($sumOfMarketPrices, $simulations, $formData['simulations']);
        }

        return $sumOfMarketPrices;
    }
    
    private function filterPercentile($marketPrices, int $computedSimulations, int $requestedSimulations): array
    {
        $filteredMarketPrices = [];
        if (empty($marketPrices)) {
            return $filteredMarketPrices;
        }
        
        $lastValues = array_column(
            $marketPrices, 
            array_key_last(current($marketPrices))
        );

        asort($lastValues, SORT_NUMERIC);
        
        $offset = (int) (($computedSimulations - $requestedSimulations) / 2);
        $lastValuesKeys = array_slice(array_keys($lastValues), $offset, $requestedSimulations);
        
        foreach ($lastValuesKeys as $k) {
            $filteredMarketPrices[] = $marketPrices[$k];
        }

        return $filteredMarketPrices;
    }

    private function getInitializedFormData() : array {
        $initializedFormData = [
            'years' => 0,
            
            //            'investment_rate' => 0,
            'investment_amount' => 0,
            'investment_contribution_years' => 0,
            'investment_monthly_contribution' => 0,

            //            'pension_rate' => 0,
            'pension_amount' => 0,
            'pension_contribution_years' => 0,
            'pension_monthly_contribution' => 0,

            'simulations' => 0,
            'remove_percentile' => 0,
            'withdraw_amount' => 0,
            'withdraw_year' => 0,

            'stats_amount_start' => 0,
            'stats_amount_end' => 0,
            'stats_periods' => 0,
            'stats_rate' => 0,
            'stats_probability_success' => 0,
        ];

        return $initializedFormData;
    }

    private function getFormDataMergedWithCookieData(): array
    {
        $cookiePensionPost = new Cookie(self::COOKIE_NAME);

        $initializedFormData = $this->getInitializedFormData();
        if (empty($_POST)) {
            $formData = $cookiePensionPost->getData() ?? $initializedFormData;
        } else {
            $formData = array_merge($initializedFormData, $_POST);
            $cookiePensionPost->store($formData);
        }

        return $formData;
    }
}
