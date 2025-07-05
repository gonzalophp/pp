<?php
namespace App\Tests\Entity;

use App\Service\Chart;
use PHPUnit\Framework\TestCase;

class ChartTest extends TestCase
{
    public function testOne(): void
    {
        $this->assertEquals(1,1,);
    }

    public function FunctionName() :void
    {
        $chart = new Chart();
        $chart->getImageDataBase64(300, 200);
    }

}