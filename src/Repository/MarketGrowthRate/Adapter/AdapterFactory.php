<?php

namespace App\Repository\MarketGrowthRate\Adapter;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AdapterFactory
{
    private string $fileName;

    public function __construct(private ParameterBagInterface $parameterBag)
    {
        
    }

    public function getAdapter(string $formDataMarketRate): MarketRateGrowthAdapterInterface
    {
        $path = "{$this->parameterBag->get('resources')['market_prices']['path']}/{$formDataMarketRate}";

        switch (true) {
            case (strpos($formDataMarketRate, 'Stooq') !== false) :
                $adapter = new StooqFile($path);
                break;
            default:
                $adapter = new UnhandledFile($path);
                break;
        }

        return $adapter;
    }


}