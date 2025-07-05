<?php

namespace App\Service;

use App\Helper\ScaleGenerator;

class Chart {
    private \GdImage $image;
    private $limits;
    private $scale;

    public function __construct(private ScaleGenerator $scaleGenerator) {
    }
    
    public function getImageDataBase64(int $width, int $height, ...$prices): string
    {
        $this->image = \imagecreatetruecolor($width, $height);
        if (count($prices) > 0) {
            $this->buildAxis($width, $height, $prices);
            $this->scale = $this->scaleGenerator->getScale(
                $this->limits['dataYMin'], 
                $this->limits['dataYMax']
            );
            $this->buildScale($this->limits['dataYMin'], $this->limits['dataYMax']);

            foreach ($prices as $pointValues) {
                $this->drawSequence($pointValues);
            }
        }
            
        ob_start();
        imagepng($this->image);
        $imageData = ob_get_clean();
        
        return base64_encode($imageData);
    }
    
    private function drawSequence(array $pointValues): void
    {
        $lastValue = end($pointValues);
        $totalCount = count($pointValues);
        $maxScale = end($this->scale);
        
        foreach (array_keys($pointValues) as $n => $k) {
            if (isset($previousValue)) {
                $x1 = (int) ($this->limits['drawXMin'] + ((($n - 1) / ($totalCount - 1)) * ($this->limits['drawXMax'] - $this->limits['drawXMin'])));
                $x2 = (int) ($this->limits['drawXMin'] + (($n / ($totalCount - 1)) * ($this->limits['drawXMax'] - $this->limits['drawXMin'])));
                $y1 = (int) ($this->limits['drawYMax'] - (($this->limits['drawYMax'] - $this->limits['drawYMin']) * ($previousValue / $maxScale)));
                $y2 = (int) ($this->limits['drawYMax'] - (($this->limits['drawYMax'] - $this->limits['drawYMin']) * ($pointValues[$k] / $maxScale)));
                imageline($this->image, $x1, $y1, $x2, $y2, (($lastValue > 0) ? 0x00FF00 : 0xFF0000));
                
            }
            $previousValue = $pointValues[$k];
            
        }
    }
    
    private function buildAxis(int $width, int $height, array $prices): void
    {
        $this->limits['dataXMin'] = 0;
        
        foreach ($prices as $priceSequence) {
            $this->limits['dataXMax'] = count($priceSequence);
            foreach ($priceSequence as $v) {
                if (!isset($this->limits['dataYMin'])) {
                    $this->limits['dataYMin'] = $v;
                    $this->limits['dataYMax'] = $v;
                } else {
                    if ($v < $this->limits['dataYMin']) {
                        $this->limits['dataYMin'] = $v;
                    }
                    if ($v > $this->limits['dataYMax']) {
                        $this->limits['dataYMax'] = $v;
                    }
                }
            }
        }
        
        $this->limits['drawXMin'] = $width * 0.08;
        $this->limits['drawXMax'] = $width * 0.92;
        $this->limits['drawYMin'] = $height * 0.05;
        $this->limits['drawYMax'] = $height * 0.95;
        
        imagerectangle(
            $this->image, 
            $this->limits['drawXMin'],
            $this->limits['drawYMin'], 
            $this->limits['drawXMax'], 
            $this->limits['drawYMax'], 
            0xAABBCC
        );
    }
    
    private function buildScale(): void
    {
        $maxScale = end($this->scale);                
        foreach ($this->scale as $yData) {
            $yDraw = (int) ($this->limits['drawYMax'] - (($this->limits['drawYMax'] - $this->limits['drawYMin']) * ($yData / $maxScale)));
            
            imageline(
                    $this->image, 
                    $this->limits['drawXMax'], 
                    $yDraw, 
                    $this->limits['drawXMax']+5, 
                    $yDraw, 
                    0xAABBCC
            );
            
            $yScale = number_format($yData);
            foreach (['M' => 1000000, 'K' => 1000] as $k => $v) {
                if (abs($yData/$v) > 1) {
                    $yScale = number_format($yData/$v) . $k; 
                    break;
                }
            }
            
            imagestring(
                    $this->image,
                    2,
                    $this->limits['drawXMax'] + 9,
                    $yDraw - 6,
                    $yScale,
                    0x00FF00
            );
        }
    }
}
