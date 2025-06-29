<?php
namespace App\Tests\Entity;

use App\Repository\MarketGrowthRate\Adapter\StooqFile;
use PHPUnit\Framework\TestCase;

class StooqFileTest extends TestCase
{
    public function testReadFromFile(): void
    {
        $stooqFileAdapter = new StooqFile(__DIR__ . '/ndx.csv');

        $data = $stooqFileAdapter->loadData();

        $this->assertCount(5, $data);
        $this->assertEquals($data[0],4.393);
        $this->assertEquals($data[4],5.642);
    }
}