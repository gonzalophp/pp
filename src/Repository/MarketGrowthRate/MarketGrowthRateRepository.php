<?php
namespace App\Repository;

use App\Repository\MarketGrowthRate\Adapter\MarketRateGrowthAdapterInterface;

class MarketGrowthRateRepository {
    private array $rateTable;
    
    public function __construct(MarketRateGrowthAdapterInterface $adapter) {
        $this->rateTable = $adapter->loadData();
    }
    
    public function getRandomRate(): float {
        return $this->rateTable[array_rand($this->rateTable)];
    }
}