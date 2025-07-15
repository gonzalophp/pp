<?php
namespace App\Entity;

class Market {
    public function __construct(
        private string $marketName,
        private string $marketRateSource,
        private float $startAmount,
        private float $periodicalContributionAmount = 0,
        private int $lastContributionPeriod = 0
    ) {

    }

    public function getMarketName(): string
    {
        return $this->marketName;
    }

    public function getMarketRateSource() : string 
    {
        return $this->marketRateSource;
    }

    public function getStartAmount() : float
    {
        return $this->startAmount;
    }

    public function getPeriodicalContributionAmount() : float
    {
        return $this->periodicalContributionAmount;
    }

    public function getLastContributionPeriod() : int
    {
        return $this->lastContributionPeriod;
    }
}

