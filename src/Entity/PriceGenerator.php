<?php
namespace App\Entity;

class PriceGenerator {    
    public function getSumOfPrices(array $marketRates, int $periods, array $formData)
    {
        $rates = [];
        foreach ($marketRates as $market => $marketRate) {
            $rates[$market] = array_map(
                fn() => $marketRate->getRandomRate(),
                range(0, $periods - 1)
            );
        }
        
        $withdrawStart = ((int) $formData['withdraw_year']) * 12;
        $withdrawAmount = (int) $formData['withdraw_amount'];

        $sumOfPrices = [];
        $marketPrices = [];
        foreach(range(0, $periods) as $period) {
            foreach (array_keys($marketRates) as $market) {
                if ($period === 0) {
                    $marketPrices[$market] = [$period => $formData[$market . '_amount']];
                } else {
                    $contributionPeriods = $formData[$market . '_contribution_years'] * 12;
                    if ($period >= $contributionPeriods) {
                        $periodicalContribution = 0;
                    } else {
                        $periodicalContribution = $formData[$market . '_monthly_contribution'];
                    }
                    $baseForTheMarketPeriod = ($marketPrices[$market][$period - 1] + $periodicalContribution);
                    
                    if ($baseForTheMarketPeriod > 0)  {
                        $marketPrices[$market][$period] = round($baseForTheMarketPeriod * (1 + ($rates[$market][$period - 1] / 100)), 2);
                    } else {
                        $marketPrices[$market][$period] = $baseForTheMarketPeriod;
                    }
                }
            }
            
            $withdraw = ($period >= $withdrawStart) ? $withdrawAmount : 0;
            if ($withdraw > 0) {
                $totalFromMarketsSoFar = 0;
                foreach (array_keys($marketRates) as $market) {
                    $totalFromMarketsSoFar += $marketPrices[$market][$period];
                }
                
                foreach (array_keys($marketRates) as $market) {
                    $marketPrices[$market][$period] -= (int)($withdraw * ($marketPrices[$market][$period]/$totalFromMarketsSoFar));
                }
            }
                
            
            $sumOfPrices[$period] = 0;
            foreach (array_keys($marketRates) as $market) {
                $sumOfPrices[$period] += $marketPrices[$market][$period];
            }
        }
 
        return $sumOfPrices;
    }
}