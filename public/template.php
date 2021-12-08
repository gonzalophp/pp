<?php

class Template {
    public function render(string $fileName, array $data): string
    {
        $keys = [];
        $values = [];
        foreach ($data as $key => $value) {
            $keys[] = '/\{\{' . $key . '\}\}/';
            $values[] = $value;
        }
        
        $keys[] = '/\{\{\w+\}\}/';
        $values[] = '';

        $fileContents = file_get_contents($fileName);
        $contentsWithValues = preg_replace($keys, $values, $fileContents);
        
        return $contentsWithValues;
    }
}