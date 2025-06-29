<?php

namespace App\Helper;

class FileSearch
{
    public function get(string $path, string $filter, bool $returnAbsolutePath = true) :array 
    {
        $pattern = '/' . str_replace(
            ['.', '*', '?'],
            ['\.', '.*', '.'],
            $filter
        ) . '/';

        var_export(['$pattern' => $pattern]);
        echo "\n\n";

        $csvFiles = array_filter(
            scandir(
                $path,
                SCANDIR_SORT_ASCENDING
            ),
            function($v) use ($pattern) {
                var_export(['v' => $v]);
                return preg_match($pattern, $v);                    
            } 
        );

        $csvFiles = array_values($csvFiles);
        $csvFiles = array_map(fn($v) => ($path . $v), $csvFiles);

        var_export(['$csvFiles' => $csvFiles]);

        return $csvFiles;
    }
}