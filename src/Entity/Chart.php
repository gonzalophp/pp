<?php

namespace App\Entity;


class Chart {
    private \GdImage $image;
    private $limits;
    private $scale;

    public function __construct(private int $width, private int $height) {
        $this->image = \imagecreatetruecolor($width, $height);
    }

    public function getImageDataBase64(...$prices): string
    {
        if (count($prices) > 0) {
            $this->buildAxis($prices);
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
    
    private function buildAxis(array $prices): void
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
        
        $this->limits['drawXMin'] = $this->width * 0.08;
        $this->limits['drawXMax'] = $this->width * 0.92;
        $this->limits['drawYMin'] = $this->height * 0.05;
        $this->limits['drawYMax'] = $this->height * 0.95;
        
        imagerectangle(
            $this->image, 
            $this->limits['drawXMin'],
            $this->limits['drawYMin'], 
            $this->limits['drawXMax'], 
            $this->limits['drawYMax'], 
            0xAABBCC
        );
    }
    
    private function buildScale($min, $max): void
    {
        $diff = $max-$min;
        
        $floatSegment = $diff / 5;
        for($n=0; ($floatSegment/pow(10, $n)) > 1; $n++);
        
        $finalValueBelow1 = $floatSegment / pow(10, $n);
        foreach ([0.1, 0.2, 0.5, 1] as $v) {
            if ($v > $finalValueBelow1) {
                $secondScaleValue = (int) ($v * pow(10, $n));
                break;
            }
        }
        
        for($i=0; ($this->scale[$i - 1] ?? 0) < $max; $i++) {
            if ($i===0) {
                $this->scale[$i] = 0;
            } elseif ($i===1) {
                $this->scale[$i] = $secondScaleValue;
            } else {
                $this->scale[$i] = $this->scale[$i - 1] + $secondScaleValue;
            }
        }
        
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
                if (($yData/$v) > 1) {
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
