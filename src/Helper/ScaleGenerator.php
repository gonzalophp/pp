<?php

namespace App\Helper;


class ScaleGenerator
{
    private const PREFERRED_TICK_MARKS = 6;
    private const OPERATIONAL_THRESHOLD = 1000;
    private const WORKING_TEN_EXPONENT = 3;

    public function getScale(float $a, float $b): array {
        // Ensure $min < $max
        $min = min($a, $b);
        $max = max($a, $b);
        $range = $max - $min;

        // If min == max (e.g. flat line), create a small range around it
        if ($range == 0) {
            $range = 1;
            $min -= 0.5;
            $max += 0.5;
        }

        // Candidate tick spacings (in increasing order)
        $possibleSteps = [0.1, 0.2, 0.5, 1];

        // Dynamically expand tick sizes by scaling
        foreach ([10, 100, 1000, 10000, 100000, 1e6, 1e7, 1e8] as $scale) {
            foreach ([0.1, 0.2, 0.5, 1] as $step) {
                $possibleSteps[] = $step * $scale;
                $possibleSteps[] = $step / $scale;
            }
        }

        // Sort and deduplicate
        $possibleSteps = array_unique($possibleSteps);
        sort($possibleSteps);

        // Choose a step that gives between 3 and 7 ticks
        foreach ($possibleSteps as $step) {
            $start = floor($min / $step) * $step;
            $end = ceil($max / $step) * $step;
            $tickCount = round(($end - $start) / $step) + 1;

            if ($tickCount >= 3 && $tickCount <= 7) {
                $ticks = [];
                for ($val = $start; $val <= $end + $step / 2; $val += $step) {
                    $ticks[] = round($val, 10); // prevent floating point noise
                }
                return $ticks;
            }
        }

        // Fallback (shouldn't happen): just return min and max
        return [$min, $max];
    }
}
