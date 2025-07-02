<?php
namespace App\Repository;

use App\Repository\MarketGrowthRate\Adapter\MarketRateGrowthAdapterInterface;

class MarketGrowthRateRepository {
    private array $rateTable;
    
    public function __construct(private MarketRateGrowthAdapterInterface $adapter) {
        $this->rateTable = $adapter->loadData();
    }
    
    public function getRandomRate(): float {
        if (empty($this->rateTable)) {
            return 0;
        }
        
        return $this->rateTable[array_rand($this->rateTable)];
    }
}