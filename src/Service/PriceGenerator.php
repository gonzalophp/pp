<?php
namespace App\Service;

use App\Repository\MarketGrowthRateRepository;

class PriceGenerator {
    /**
     * @param MarketGrowthRateRepository[] $marketGrowthRateRepositories
     * @param int $periods
     * @param array $formData
     */
    public function getSumOfMarketValues(
        array $marketRates, 
        int $periods, 
        array $formData,
        int $withdrawStartPeriod,
        int $withdrawAmountPerPeriod
    ){
        $markets = array_keys($marketRates);
        $sumOfMarketValues = [];
        $marketPrices = [];
        foreach(range(0, $periods) as $period) {
            foreach ($markets as $market) {
                if ($period === 0) {
                    $marketPrices[$market] = [$period => $formData["market_{$market}_amount"]];
                } else {
                    $contributionPeriods = $formData["market_{$market}_contribution_years"] * 12;
                    if ($period >= $contributionPeriods) {
                        $periodicalContribution = 0;
                    } else {
                        $periodicalContribution = $formData["market_{$market}_monthly_contribution"];
                    }
                    $baseForTheMarketPeriod = ($marketPrices[$market][$period - 1] + $periodicalContribution);
                    
                    if ($baseForTheMarketPeriod > 0)  {
                        $marketPrices[$market][$period] = round($baseForTheMarketPeriod * (1 + ($marketRates[$market][$period - 1] / 100)), 2);
                    } else {
                        $marketPrices[$market][$period] = $baseForTheMarketPeriod;
                    }
                }
            }
            
            if (($withdrawAmountPerPeriod > 0) && ($period >= $withdrawStartPeriod)) {
                $currentMarketValues = [];
                foreach ($markets as $market) {
                    $currentMarketValues[$market] = $marketPrices[$market][$period];
                }
                $percentageOfTotal = $this->getPercentagesOfTotal($currentMarketValues);
                foreach ($percentageOfTotal as $market => $percentage) {
                    $marketPrices[$market][$period] -= (int) (($withdrawAmountPerPeriod * $percentage) / 100);
                }
            }
                
            
            $sumOfMarketValues[$period] = 0;
            foreach ($markets as $market) {
                $sumOfMarketValues[$period] += $marketPrices[$market][$period];
            }
        }
 
        return $sumOfMarketValues;
    }

    private function getPercentagesOfTotal(array $marketValues): array
    {
        $totalValue = 0;
        foreach ($marketValues as $market => $value) {
            $totalValue += $value;
        }

        $tempPercentagesOfTotal = [];

        foreach ($marketValues as $market => $value) {
            $tempPercentagesOfTotal[$market] = (100000 * $value) / $totalValue;
        }

        $percentagesOfTotal = [];
        foreach ($tempPercentagesOfTotal as $market => $tempPercentage) {
            $tempPercentagesOfTotal[$market] = round($tempPercentage / 1000, 2);
        }
        

        return $percentagesOfTotal;        
    }
}