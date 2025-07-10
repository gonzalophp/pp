<?php
namespace App\Tests\Service;

use App\Helper\ScaleGenerator;
use App\Service\Chart;
use PHPUnit\Framework\TestCase;

class ChartTest extends TestCase
{
    public function FunctionName() :void
    {
        $chart = new Chart(new ScaleGenerator());
        $chart->getImageDataBase64(300, 200);
    }

}