<?php

require '../vendor/autoload.php';

class Run
{
    private int $part1 = 0;

    public function run()
    {
        $handle = fopen('input.txt', 'rb');

        $puzzles = [];
        while ($line = fgets($handle)) {
            $line = trim($line);
            foreach (str_split($line) as $pos => $char) {
                $puzzles[$pos + 1][] = $char;
            }
        }

        foreach ($puzzles as $i => $column) {
            $movedRocks = $this->moveRocks($column);
            $this->part1 += $this->calcRockLoad($movedRocks);
        }

        dd($this->part1);
    }

    private function moveRocks(array $column): array
    {
        $lastEmptyIndex = null;
        foreach ($column as $index => $char) {
            switch ($char) {
                case '.':
                    if ($lastEmptyIndex === null) {
                        $lastEmptyIndex = $index;
                    }
                    break;
                case 'O':
                    if ($lastEmptyIndex !== null) {
                        $column[$lastEmptyIndex] = 'O';
                        if (($column[$lastEmptyIndex + 1]) === '.') {
                            $lastEmptyIndex = $lastEmptyIndex + 1;
                        } else {
                            $lastEmptyIndex = $index;
                        }
                        $column[$index] = '.';
                    }
                    break;
                case '#':
                    $lastEmptyIndex = null;
                    break;
            }
        }

        return $column;
    }

    private function calcRockLoad(array $column): int
    {
        $load = 0;
        $column = array_reverse($column);
        foreach ($column as $index => $char) {
            if ($char === 'O') {
                $load += $index + 1;
            }
        }

        return $load;
    }
}

(new Run())->run();


