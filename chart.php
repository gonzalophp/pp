<?php

class Chart {
    private GdImage $image;
    private $limits;

    public function __construct(private int $width, private int $height) {
        $this->image = imagecreatetruecolor($width, $height);        
    }

    public function getImageDataBase64(...$prices): string
    {
        $this->buildAxis($prices);
        
        foreach($prices as $pointValues) {
            $this->drawSequence($pointValues);
        }
        ob_start();
        imagepng($this->image);
        $imageData = ob_get_clean();

        return base64_encode($imageData);
    }
    
    private function drawSequence(array $pointValues): void
    {
        $totalCount = count($pointValues);
        foreach (array_keys($pointValues) as $n => $k) {
            if ($n > 0) {
                $x1 = $this->limits['drawXMin'] + ((($n - 1) / ($totalCount - 1)) * ($this->limits['drawXMax'] - $this->limits['drawXMin']));
                $x2 = $this->limits['drawXMin'] + (($n / ($totalCount - 1)) * ($this->limits['drawXMax'] - $this->limits['drawXMin']));
                $y1 = $this->limits['drawYMax'] - (($this->limits['drawYMax'] - $this->limits['drawYMin']) * ($previousValue / $this->limits['dataYMax']));
                $y2 = $this->limits['drawYMax'] - (($this->limits['drawYMax'] - $this->limits['drawYMin']) * ($pointValues[$k] / $this->limits['dataYMax']));
                imageline($this->image, $x1, $y1, $x2, $y2, 0x1188FF);
                
            }
//            var_export(['limits' => $this->limits, 'previous' => $previousValue, 'current' => $pointValues[$k]]);
            $previousValue = $pointValues[$k];
            
        }
        
        var_export(['last-price' => number_format($previousValue, 2)]);
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
        
        $this->limits['drawXMin'] = $this->width * 0.05;
        $this->limits['drawXMax'] = $this->width * 0.95;
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
}
