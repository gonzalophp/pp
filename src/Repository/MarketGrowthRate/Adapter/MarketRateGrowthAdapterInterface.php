<?php

namespace App\Repository\MarketGrowthRate\Adapter;

interface MarketRateGrowthAdapterInterface
{
    public function loadData() : array;
}