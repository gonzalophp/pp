<?php

namespace App\Repository\MarketGrowthRate\Adapter;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AdapterFactory
{
    private string $fileName;

    public function __construct(private ParameterBagInterface $parameterBag)
    {
        
    }

    public function getAdapter(string $formDataMarketRateSource): MarketRateGrowthAdapterInterface
    {
        $path = "{$this->parameterBag->get('resources')['market_prices']['path']}/{$formDataMarketRateSource}";

        switch (true) {
            case (strpos($formDataMarketRateSource, 'Stooq') !== false) :
                $adapter = new StooqFile($path);
                break;
            default:
                $adapter = new UnhandledFile($path);
                break;
        }

        return $adapter;
    }


}