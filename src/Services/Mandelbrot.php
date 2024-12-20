<?php
namespace Cookbook\Services;

class Mandelbrot
{
    public static int $bailout = 16;
    public static int $maxIterations = 50_000;
    public function generate()
    {
        $txt = '';
        $d1  = microtime(1);
        for ($y = -39; $y < 39; $y++) {
            for ($x = -39; $x < 39; $x++) {
                if ($this->iterate($x/40.0, $y/40.0) === 0) 
                    $txt .= '*';
                else
                    $txt .= ' ';
            }
            $txt .= PHP_EOL;
        }
        $d2 = microtime(1);
        $diff = $d2 - $d1;
        return $txt . sprintf("Elapsed Time: %0.3f\n", $diff);
    }

    public function iterate($x,$y)
    {
        $cr = $y-0.5;
        $ci = $x;
        $zr = 0.0;
        $zi = 0.0;
        $i = 0;
        while (true) {
            $i++;
            $temp = $zr * $zi;
            $zr2 = $zr * $zr;
            $zi2 = $zi * $zi;
            $zr = $zr2 - $zi2 + $cr;
            $zi = $temp + $temp + $ci;
            if ($zi2 + $zr2 > self::$bailout)
                return $i;
            if ($i > self::$maxIterations)
                return 0;
        }    
    }
}
