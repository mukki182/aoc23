<?php

require '../vendor/autoload.php';

class Part2
{
    private array $matrix = [];
    private int $countRows;
    private int $countColumns;
    private array $beams = [];
    private array $solvedBeams = [];
    private array $filledMatrix = [];

    public function run(bool $test = false): void
    {
        $this->readInput($test);

        $maxEnergized = 0;

        for ($i = 0; $i < $this->countRows; $i++) {
            echo $i . PHP_EOL;
            $this->reset();
            $startBeam = [$i, 0, 'R'];
            $this->beams = [$startBeam];
            while ($beam = array_pop($this->beams)) {
                $this->solveBeam($beam);
            }
            $energized = $this->countFilled();
            if ($energized > $maxEnergized) {
                $maxEnergized = $energized;
            }

            $this->reset();
            $startBeam = [$i, $this->countColumns - 1, 'L'];
            $this->beams = [$startBeam];
            while ($beam = array_pop($this->beams)) {
                $this->solveBeam($beam);
            }
            $energized = $this->countFilled();
            if ($energized > $maxEnergized) {
                $maxEnergized = $energized;
            }
        }

        for ($i = 0; $i < $this->countColumns; $i++) {
            $this->reset();
            $startBeam = [0, $i, 'D'];
            $this->beams = [$startBeam];
            while ($beam = array_pop($this->beams)) {
                $this->solveBeam($beam);
            }
            $energized = $this->countFilled();
            if ($energized > $maxEnergized) {
                $maxEnergized = $energized;
            }

            $this->reset();
            $startBeam = [$this->countRows - 1, $i, 'U'];
            $this->beams = [$startBeam];
            while ($beam = array_pop($this->beams)) {
                $this->solveBeam($beam);
            }
            $energized = $this->countFilled();
            if ($energized > $maxEnergized) {
                $maxEnergized = $energized;
            }
        }

        echo $maxEnergized;
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
                        break;
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

    private function reset(): void
    {
        $this->solvedBeams = [];
        $this->filledMatrix = array_fill(0, $this->countRows, array_fill(0, $this->countColumns, '.'));
    }
}

(new Part2())->run(false);
