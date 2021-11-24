<?php

class PriceGenerator {
    public function getPrices(
        float $startPrice, 
        array $rates, 
        float $periodContribution
    ): array {
        $prices = [$startPrice];
        
        for ($n=1; $n <= count($rates); $n++) {
            
            $prices[$n] = round(($prices[$n-1]+ $periodContribution) * (1 + ($rates[$n-1]/100)), 2);
        }
        
        return $prices;
    }
}