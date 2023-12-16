<?php

require '../vendor/autoload.php';

class Part2
{
    private array $matrix = [];
    private array $startPosition = [];
    private array $replacement = [
        '-' => '─',
        '|' => '│',
        'L' => '└',
        'J' => '┘',
        '7' => '┐',
        'F' => '┌',
        '.' => ' '
    ];

    public function run(bool $test = false): void
    {
        $this->readInput($test);


        $prev = $this->startPosition;
        $current = [$this->startPosition[0], $this->startPosition[1] - 1];
        while (true) {
            $next = $this->nextStep($prev, $current);
            if ($next === null) {
                break;
            }
            $prev = $current;
            $current = $next;
        }

        echo $this->calcInside().PHP_EOL;
        $this->drawMatrix();
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        $i = 0;
        while ($line = trim(fgets($handle))) {
            if (($pos = strpos($line, 'S')) !== false) {
                $this->startPosition = [$i, $pos];
            }
            $this->matrix[$i++] = str_split($line);
        }
    }

    private function nextStep(array $previousPosition, array $currentPosition): ?array
    {
        [$previousRow, $previousColumn] = $previousPosition;
        [$currentRow, $currentColumn] = $currentPosition;

        $nextRow = $currentRow;
        $nextColumn = $currentColumn;
        $up = $currentRow - 1;
        $down = $currentRow + 1;
        $left = $currentColumn - 1;
        $right = $currentColumn + 1;
        switch ($this->matrix[$currentRow][$currentColumn]) {
            case '|':
                if ($currentRow < $previousRow) {
                    $nextRow = $up;
                } else {
                    $nextRow = $down;
                }
                break;
            case '-':
                if ($currentColumn < $previousColumn) {
                    $nextColumn = $left;
                } else {
                    $nextColumn = $right;
                }
                break;
            case 'L':
                if ($currentRow > $previousRow) {
                    $nextColumn = $right;
                } else {
                    $nextRow = $up;
                }
                break;
            case 'J':
                if ($currentRow > $previousRow) {
                    $nextColumn = $left;
                } else {
                    $nextRow = $up;
                }
                break;
            case '7':
                if ($currentRow < $previousRow) {
                    $nextColumn = $left;
                } else {
                    $nextRow = $down;
                }
                break;
            case 'F':
                if ($currentRow < $previousRow) {
                    $nextColumn = $right;
                } else {
                    $nextRow = $down;
                }
                break;
            case 'S':
                return null;
        }

        $this->matrix[$currentRow][$currentColumn] = str_replace(
            array_keys($this->replacement),
            array_values($this->replacement),
            $this->matrix[$currentRow][$currentColumn]
        );

        return [$nextRow, $nextColumn];
    }

    private function calcInside(): int
    {
        $pipesChars = [
            '-' => '─',
            '|' => '│',
            'L' => '└',
            'J' => '┘',
            '7' => '┐',
            'F' => '┌',
            '.' => ' '
        ];
        $total = 0;
        foreach ($this->matrix as $rowIndex => $row) {
            foreach ($row as $columnIndex => $char) {
                if (in_array($char, $this->replacement + ['S']) || $columnIndex < 1) {
                    continue;
                }

                $crossings = 0;
                for ($i = 0; $i < $columnIndex; $i++) {
                    if (in_array($this->matrix[$rowIndex][$i], ['│', '┘', '└'])) {
                        $crossings++;
                    }
                }

                if ($crossings % 2 !== 0) {
                    $this->matrix[$rowIndex][$columnIndex] = 'X';
                    $total++;
                }
            }
        }

        return $total;
    }

    private function drawMatrix(): void
    {
        foreach ($this->matrix as $row) {
            echo implode($row) . PHP_EOL;
        }
    }
}

(new Part2())->run(false);
