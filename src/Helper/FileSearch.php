<?php

namespace App\Helper;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileSearch
{
    public function get(string $directoryPath, string $fileNamePattern) :array 
    {
        $directoryPath = rtrim($directoryPath, DIRECTORY_SEPARATOR) . 
            DIRECTORY_SEPARATOR;

        $fileNameRegexPattern = $this->getFilePattern($fileNamePattern);

        $directoryIterator = new \DirectoryIterator(
            $directoryPath
        );

        $regexIterator = new \RegexIterator(
            $directoryIterator, 
            $fileNameRegexPattern,
            \RegexIterator::GET_MATCH
        );

        $fileNames = [];
        foreach ($regexIterator as $matches) {
            foreach ($matches as $match) {
                $fileNames[] = $match;
            }
        }

        sort($fileNames);

        return $fileNames;
    }

    public function getRecursive(string $directoryPath, string $fileNamePattern): \Generator
    {
        $directoryPath = rtrim($directoryPath, DIRECTORY_SEPARATOR) . 
            DIRECTORY_SEPARATOR;

        $fileNameRegexPattern = $this->getFilePattern($directoryPath . $fileNamePattern);

        $recursiveDirectoryIterator = new \RecursiveDirectoryIterator(
            $directoryPath
        );

        $recursiveIteratorIterator = new \RecursiveIteratorIterator(
            $recursiveDirectoryIterator
        );

        $regexIterator = new \RegexIterator(
            $recursiveIteratorIterator, 
            $fileNameRegexPattern,
            \RegexIterator::GET_MATCH
        );

        $absoluteFileNames = [];
        foreach ($regexIterator as $matches) {
            foreach ($matches as $match) {
                $absoluteFileNames[] = $match;
            }
        }

        sort($absoluteFileNames);
        $relativeFileNames = array_map(
            fn($v) => str_replace($directoryPath, '',$v), 
            $absoluteFileNames
        );

        yield from $relativeFileNames;
    }    

    private function getFilePattern(string $fileNamePattern) : string
    {
        $fileNameRegexPattern = 
            '/^' . 
            str_replace(
                ['/', '.', '*', '?'],
                ['\/', '\.', '.*', '.'],
                $fileNamePattern
            ) . 
            '$/';

        return $fileNameRegexPattern;
    }
}