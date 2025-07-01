<?php
namespace App\Tests\Helper;

use App\Helper\FileSearch;
use PHPUnit\Framework\TestCase;

class FileSearchTest extends TestCase
{
    public function testGet1(): void
    {
        $fileSearch = new FileSearch();

        $files = $fileSearch->get(__DIR__ . '/fileSearchDir/', 'a.csv');

        $this->assertCount(1, $files);
        $this->assertEquals('a.csv', $files[0]);
    }

    public function testGetWildcharCharacter1(): void
    {
        $fileSearch = new FileSearch();

        $files = $fileSearch->get(__DIR__ . '/fileSearchDir/', '*.csv');

        $this->assertCount(1, $files);
        $this->assertEquals('a.csv', $files[0]);
    }

    public function testGetWildcharCharacter2(): void
    {
        $fileSearch = new FileSearch();

        $files = $fileSearch->get(__DIR__ . '/fileSearchDir/dir2/dir3', '*.csv');

        $this->assertCount(2, $files);
        $this->assertEquals('c.csv', $files[0]);
        $this->assertEquals('d.csv', $files[1]);
    }

    public function testGetRecursiveWithFileName1(): void
    {
        $fileSearch = new FileSearch();

        $fileIterator = $fileSearch->getRecursive(
            __DIR__ . '/fileSearchDir', 
            'a.csv'
        );

        $fileArray = iterator_to_array($fileIterator);

        $this->assertCount(1, $fileArray);
        $this->assertEquals('a.csv', $fileArray[0]);
    }

    public function testGetRecursiveWithFileNamePattern1(): void
    {
        $fileSearch = new FileSearch();

        $fileIterator = $fileSearch->getRecursive(
            __DIR__ . '/fileSearchDir', 
            'a*.csv'
        );

        $fileArray = iterator_to_array($fileIterator);

        $this->assertCount(1, $fileArray);
        $this->assertEquals('a.csv', $fileArray[0]);
    }

    public function testGetRecursiveWithFileNamePattern2(): void
    {
        $fileSearch = new FileSearch();

        $fileIterator = $fileSearch->getRecursive(
            __DIR__ . '/fileSearchDir/', 
            '*.csv'
        );

        $fileArray = iterator_to_array($fileIterator);

        $this->assertCount(4, $fileArray);
        $this->assertEquals('a.csv', $fileArray[0]);
        $this->assertEquals('dir1/b.csv', $fileArray[1]);
        $this->assertEquals('dir2/dir3/c.csv', $fileArray[2]);
        $this->assertEquals('dir2/dir3/d.csv', $fileArray[3]);
    }
}