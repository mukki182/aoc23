<?php

require '../vendor/autoload.php';

class Part1
{
    private array $matrix = [];
    private int $countRows;
    private int $countColumns;#
    private array $beams = [];
    private array $solvedBeams = [];
    private array $filledMatrix = [];

    public function run(bool $test = false): void
    {
        $this->readInput($test);
        $startBeam = [0, 0, 'R'];
        $this->beams = [$startBeam];
        while ($beam = array_pop($this->beams)) {
            $this->solveBeam($beam);
        }
        echo $this->countFilled();
    }

    private function solveBeam(array $beam): void
    {
        $key = implode('|', $beam);
        if (in_array($key, $this->solvedBeams)) {
            return;
        }

        [$currentRow, $currentColumn, $direction] = $beam;


        if (min($currentRow, $currentColumn) < 0 || $currentRow >= $this->countRows || $currentColumn >= $this->countColumns) {
            return;
        }


        $this->filledMatrix[$currentRow][$currentColumn] = '#';
        switch ($this->matrix[$currentRow][$currentColumn]) {
            case '.':
                switch ($direction) {
                    case 'R':
                        $this->addBeam([$currentRow, $currentColumn + 1, 'R']);
                        break;
                    case 'L':
                        $this->addBeam([$currentRow, $currentColumn - 1, 'L']);
                        break;
                    case 'U':
                        $this->addBeam([$currentRow - 1, $currentColumn, 'U']);
                        break;
                    case 'D':
                        $this->addBeam([$currentRow + 1, $currentColumn, 'D']);
                        break;
                }
                break;
            case '-':
                switch ($direction) {
                    case 'R':
                        $this->addBeam([$currentRow, $currentColumn + 1, 'R']);
                        break;
                    case 'L':
                        $this->addBeam([$currentRow, $currentColumn - 1, 'L']);
                        break;
                    case 'U':
                    case 'D':
                        $this->addBeam([$currentRow, $currentColumn - 1, 'L']);
                        $this->addBeam([$currentRow, $currentColumn + 1, 'R']);
                        break;
                }
                break;
            case '|':
                switch ($direction) {
                    case 'R':
                    case 'L':
                        $this->addBeam([$currentRow - 1, $currentColumn, 'U']);
                        $this->addBeam([$currentRow + 1, $currentColumn, 'D']);
                        break;
                    case 'U':
                        $this->addBeam([$currentRow - 1, $currentColumn, 'U']);
                    case 'D':
                        $this->addBeam([$currentRow + 1, $currentColumn, 'D']);
                        break;
                }
                break;
            case '\\':
                switch ($direction) {
                    case 'R':
                        $this->addBeam([$currentRow + 1, $currentColumn, 'D']);
                        break;
                    case 'L':
                        $this->addBeam([$currentRow - 1, $currentColumn, 'U']);
                        break;
                    case 'U':
                        $this->addBeam([$currentRow, $currentColumn - 1, 'L']);
                        break;
                    case 'D':
                        $this->addBeam([$currentRow, $currentColumn + 1, 'R']);
                        break;
                }
                break;
            case '/':
                switch ($direction) {
                    case 'R':
                        $this->addBeam([$currentRow - 1, $currentColumn, 'U']);
                        break;
                    case 'L':
                        $this->addBeam([$currentRow + 1, $currentColumn, 'D']);
                        break;
                    case 'U':
                        $this->addBeam([$currentRow, $currentColumn + 1, 'R']);
                        break;
                    case 'D':
                        $this->addBeam([$currentRow, $currentColumn - 1, 'L']);
                        break;
                }
                break;
        }
        $this->solvedBeams[] = $key;
    }

    private function addBeam(array $beam): void
    {
        $this->beams[] = $beam;
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        $i = 0;
        while ($line = trim(fgets($handle))) {
            $this->matrix[$i++] = str_split($line);
        }
        $this->countRows = count($this->matrix);
        $this->countColumns = count($this->matrix[0]);
        $this->filledMatrix = array_fill(0, $this->countRows, array_fill(0, $this->countColumns, '.'));
    }

    private function countFilled(): int
    {
        dump(array_map('implode', $this->filledMatrix));
        $total = 0;
        foreach ($this->filledMatrix as $row) {
            foreach ($row as $char) {
                if ($char === '#') {
                    $total++;
                }
            }
        }

        return $total;
    }
}

(new Part1())->run(false);
