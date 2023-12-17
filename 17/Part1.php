<?php

require '../vendor/autoload.php';

class Part1
{
    private array $lines = [];
    private int $endRow;
    private int $endColumn;
    private SplMinHeap $queue;
    private array $visited = [];
    private float $minPath = INF;

    private const MOVES = [
        [0, -1], #left
        [1, 0], #up
        [0, 1], #right
        [-1, 0] #down
    ];

    public function __construct()
    {
        $this->queue = new SplMinHeap();
    }

    public function run(bool $test = false): void
    {
        $this->readInput($test);
        $this->queue->insert([0, [0, 0, -5, 0]]);
        while (!$this->queue->isEmpty()) {
            $this->solveQueue();
        }
        echo $this->minPath;
    }

    private function solveQueue(): void
    {
        [$pathLength, $point] = $this->queue->extract();
        [$row, $column, $direction, $distance] = $point;
        $key = implode(',', $point);

        if (array_key_exists($key, $this->visited)) {
            return;
        }

        $this->visited[$key] = true;

        if ($row === $this->endRow && $column === $this->endColumn) {
            $this->minPath = min($pathLength, $this->minPath);

            return;
        }

        foreach (self::MOVES as $nextDirection => $move) {
            $nextRow = $row + $move[0];
            $nextColumn = $column + $move[1];

            if (
                $nextRow < 0 || $nextColumn < 0 ||
                $nextRow > $this->endRow || $nextColumn > $this->endColumn ||
                ($nextDirection + 2) % 4 === $direction
            ) {
                continue;
            }

            $nextDistance = ($nextDirection === $direction) ? $distance + 1 : 1;
            if ($nextDistance > 3) {
                continue;
            }

            $nextPathLength = $pathLength + (int)$this->lines[$nextRow][$nextColumn];
            $this->queue->insert([$nextPathLength, [$nextRow, $nextColumn, $nextDirection, $nextDistance]]);
        }
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        while ($line = trim(fgets($handle))) {
            $this->lines[] = $line;
        }
        $this->endRow = count($this->lines) - 1;
        $this->endColumn = strlen($this->lines[0]) - 1;
    }
}

(new Part1())->run(false);
