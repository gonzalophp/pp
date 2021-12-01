<?php

class PriceGenerator {    
    public function getSumOfPrices(array $marketRates, int $periods, array $formData)
    {
        $marketPrices = [];
        
        // $markets = ['investment', 'pension'];
        foreach ($marketRates as $market => $marketRate) {
            $rates = array_map(
                    fn() => $marketRate->getRandomRate(),
                    range(1, $periods)
            );
            
            $contributionPeriods = $formData[$market . '_contribution_years'] * 12;
            $marketPrices[$market] = [$formData[$market . '_amount']];
            $periodicalContribution = $formData[$market . '_monthly_contribution'];
            for ($n = 1; $n <= count($rates); $n++) {
                if ($n > $contributionPeriods) {
                    $periodicalContribution = 0;
                }
                $marketPrices[$market][$n] = round(($marketPrices[$market][$n - 1] + $periodicalContribution) * (1 + ($rates[$n - 1] / 100)), 2);
            }
        }
        
        $numberOfPrices = count(reset($marketPrices));
        $sumOfPrices = [];
        for ($n=0; $n < $numberOfPrices; $n++) {
            $sumOfPrices[] = array_sum(array_column($marketPrices, $n));
        }
            
        return $sumOfPrices;
    }
}