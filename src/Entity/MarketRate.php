<?php
namespace App\Entity;

class MarketRate {
    private array $rateTable;
    
    public function __construct(private string $fileName) {
        $this->initialize();
    }
    
    private function initialize()
    {
        $f = fopen($this->fileName, 'r');
        
        // Date	Open	High	Low	Close	Volume
        $headers = fgetcsv($f);
        
        while ($row = fgetcsv($f)) {
            $row = array_combine($headers, array_pad($row, count($headers), 0));

            if (isset($previousRow)) {
                $growth = (100*($row['Open']-$previousRow['Open']))/$previousRow['Open'];
                $roundGrowth = round($growth, 3);
                $this->rateTable[] = $roundGrowth;
            }

            $previousRow = $row;
        }
    }
    
    public function getRandomRate(): float {
        return $this->rateTable[array_rand($this->rateTable)];
    }
}