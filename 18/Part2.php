<?php

require '../vendor/autoload.php';

class Part2
{
    private array $lines = [];
    private array $points = [];
    private int $perimeter = 0;

    private const MOVES = [
        [0, 1], #right
        [1, 0], #down
        [0, -1], #left
        [-1, 0] #up
    ];

    public function run(bool $test = false): void
    {
        $this->points[] = [0, 0];
        $this->readInput($test);

        foreach ($this->lines as $line) {
            $this->solveLine($line);
        }
        echo number_format($this->perimeter / 2 + $this->shoelaceArea() + 1, 0, '', '');
    }

    private function shoelaceArea(): float
    {
        $total = 0;
        $count = count($this->points);
        [$firstX, $firstY] = $this->points[0];
        $prevX = $firstX;
        $prevY = $firstY;
        for ($i = 1; $i < $count; $i++) {
            [$nextX, $nextY] = $this->points[$i];
            $total += $this->product($prevX, $prevY, $nextX, $nextY);
            $prevX = $nextX;
            $prevY = $nextY;
        }

        return abs($total) / 2.0;
    }

    private function product($x1, $y1, $x2, $y2): float
    {
        return $x1 * $y2 - $y1 * $x2;
    }

    private function solveLine(array $line): void
    {
        [, , $hexString] = $line;
        $steps = hexdec(substr($hexString, 2, 5));
        $direction = substr($hexString, 7, 1);
        [$row, $column] = end($this->points);
        [$rowChange, $columnChange] = self::MOVES[$direction];
        $this->perimeter += $steps;
        $this->points[] = [$row + $rowChange * $steps, $column + $columnChange * $steps];
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        while ($line = trim(fgets($handle))) {
            $this->lines[] = explode(' ', $line);
        }
    }
}

(new Part2())->run(false);
