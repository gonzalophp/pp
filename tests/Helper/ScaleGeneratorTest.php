<?php
namespace App\Tests\Helper;

use App\Helper\ScaleGenerator;
use PHPUnit\Framework\TestCase;

class ScaleGeneratorTest extends TestCase
{
    public function testScalePositive(): void
    {
        $scaleGenerator = new ScaleGenerator();        
        $scale = $scaleGenerator->getScale(9, 20);
        $this->assertEquals(
            [8, 10, 12, 14, 16, 18, 20], 
        $scale
        );
    }

    public function testScaleDecimal1(): void
    {
        $scaleGenerator = new ScaleGenerator();        
        $scale = $scaleGenerator->getScale(1, 2);
        $this->assertEquals(
            [1, 1.2, 1.4, 1.6, 1.8, 2], 
        $scale
        );
    }

    public function testScaleDecimal2(): void
    {
        $scaleGenerator = new ScaleGenerator();        
        $scale = $scaleGenerator->getScale(1, 6);
        $this->assertEquals(
            [1, 2, 3, 4, 5, 6], 
        $scale
        );
    }
}