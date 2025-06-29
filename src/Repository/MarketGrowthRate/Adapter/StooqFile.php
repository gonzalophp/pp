<?php

namespace App\Repository\MarketGrowthRate\Adapter;

class StooqFile implements MarketRateGrowthAdapterInterface
{
    private string $fileName;

    public function __construct($source)
    {
        $this->fileName = $source;
    }

    public function loadData() : array
    {
        $rateTable = [];

        $f = fopen($this->fileName, 'r');
        
        // Date	Open	High	Low	Close	Volume
        $headers = fgetcsv($f);
        
        while ($row = fgetcsv($f)) {
            $row = array_combine(
                $headers, 
                array_pad($row, count($headers), 0)
            );

            if (isset($previousRow)) {
                $growth = (100*($row['Open']-$previousRow['Open']))/$previousRow['Open'];
                $roundGrowth = round($growth, 3);
                $rateTable[] = $roundGrowth;
            }

            $previousRow = $row;
        }

        fclose($f);

        return $rateTable;
    }
}