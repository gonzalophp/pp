<?php

namespace App\Repository\MarketGrowthRate\Adapter;

class UnhandledFile implements MarketRateGrowthAdapterInterface
{
    public function __construct($source)
    {
    }

    public function loadData() : array
    {
        return [];
    }
}