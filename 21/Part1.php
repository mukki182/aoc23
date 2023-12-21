<?php

require '../vendor/autoload.php';

class Part1
{
    private array $matrix = [];
    private array $startPostion = [];
    private SplMaxHeap $queue;
    private int $total = 0;

    private const MOVES = [
        [0, -1], #left
        [1, 0], #up
        [0, 1], #right
        [-1, 0] #down
    ];

    public function __construct()
    {
        $this->queue = new SplMaxHeap();
    }

    public function run(bool $test = false): void
    {
        $this->readInput($test);
        $this->printMatrix();
        $this->queue->insert([64, ...$this->startPostion]);
        while (!$this->queue->isEmpty()) {
            $next = $this->queue->extract();
            $this->walk(...$next);
        }
        $this->printMatrix();

        $total = 0;
        foreach ($this->matrix as $row) {
            foreach ($row as $column) {
                if ($column === 'O') {
                    $total++;
                }
            }
        }

        echo $total;
    }

    private function walk(int $stepsLeft, int $currentRow, int $currentColumn): void
    {
        if ($this->matrix[$currentRow][$currentColumn] !== '.') {
            return;
        }

        $this->matrix[$currentRow][$currentColumn] = ($stepsLeft % 2 === 0) ? 'O' : 'X';

        if (($nextSteps = --$stepsLeft) < 0) {
            return;
        }

        foreach (self::MOVES as $move) {
            $nextRow = $currentRow + $move[0];
            $nextColumn = $currentColumn + $move[1];
            if ($this->matrix[$nextRow][$nextColumn] !== '.') {
                continue;
            }
            $this->queue->insert([$nextSteps, $nextRow, $nextColumn]);
        }
    }

    private function printMatrix(): void
    {
        echo PHP_EOL;
        foreach ($this->matrix as $row) {
            echo implode($row) . PHP_EOL;
        }
        echo PHP_EOL;
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        $row = 0;
        while (false !== $line = fgets($handle)) {
            $line = trim($line);
            $chars = str_split($line);
            foreach ($chars as $column => $char) {
                if ($char === 'S') {
                    $this->startPostion = [$row, $column];
                    $char = '.';
                }
                $this->matrix[$row][$column] = $char;
            }
            $row++;
        }
    }
}

(new Part1())->run(false);
