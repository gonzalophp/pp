<?php
namespace App\Tests\Helper;

use App\Helper\FileSearch;
use PHPUnit\Framework\TestCase;

class FileSearchTest extends TestCase
{
    public function testGet(): void
    {
        $fileSearch = new FileSearch();

        $files = $fileSearch->get(__DIR__ . '/fileSearchDir/', '*.csv', false);

        $this->assertCount(1, $files);
        $this->assertEquals(__DIR__ . '/fileSearchDir/a.csv', $files[0]);
    }

//     public function testReadFromFile(): void
//     {
//         $fileSearch = new FileSearch();

//         $files = $fileSearch->get(__DIR__ . '/fileSearchDir/', '*.csv', false);
// echo 'eeeeeeeeeeeeeeeeeeeeeeee';
//         $this->assertCount(3, $files);
//         $this->assertEquals($files[0],__DIR__ . '/fileSearchDir/a.csv');
//         $this->assertEquals($files[1],__DIR__ . '/fileSearchDir/dir1/b.csv');
//         $this->assertEquals($files[2],__DIR__ . '/fileSearchDir/dir2/dir3/c.csv');
//     }
}