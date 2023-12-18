<?php

require '../vendor/autoload.php';

class Part1
{
    private array $lines = [];
    private array $points = [];
    private int $total = 0;

    private const MOVES = [
        'L' => [0, -1], #left
        'D' => [1, 0], #up
        'R' => [0, 1], #right
        'U' => [-1, 0] #down
    ];

    public function run(bool $test = false): void
    {
        $this->points[] = [0, 0];
        $this->readInput($test);

        foreach ($this->lines as $line) {
            $this->solveLine($line);
        }
        array_pop($this->points);
        echo $this->total / 2 + $this->shoelaceArea() + 1;
    }

    private function shoelaceArea(): float
    {
        $total = 0;
        $count = count($this->points);
        [$firstX, $firstY] = $this->points[0];
        $prevX = $firstX;
        $prevY = $firstY;
        for ($i = 1; $i < $count - 1; $i++) {
            [$nextX, $nextY] = $this->points[$i];
            $total += $this->product($prevX, $prevY, $nextX, $nextY);
            $prevX = $nextX;
            $prevY = $nextY;
        }

        $total += $this->product($prevX, $prevY, $firstX, $firstY);

        return abs($total) / 2.0;
    }

    private function product($x1, $y1, $x2, $y2): float
    {
        return $x1 * $y2 - $y1 * $x2;
    }

    private function solveLine(array $line): void
    {
        [$direction, $steps] = $line;
        [$row, $column] = end($this->points);
        [$rowChange, $columnChange] = self::MOVES[$direction];
        $this->total += $steps;
        for ($i = 1; $i <= $steps; $i++) {
            $this->points[] = [$row + $rowChange * $i, $column + $columnChange * $i];
        }
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

(new Part1())->run(false);
