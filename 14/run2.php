<?php

require '../vendor/autoload.php';

class Run
{
    private array $subStringCache = [];
    private array $matrix = [];
    private array $hashes = [];
    private int $cycleStart;
    private int $cycleLength = 0;
    private string $cycleStartHash = '';
    private int $columnCount;

    public function run()
    {
        $handle = fopen('input.txt', 'rb');

        $row = 0;
        while ($line = fgets($handle)) {
            $line = trim($line);
            foreach (str_split($line) as $pos => $char) {
                $this->matrix[$row][$pos] = $char;
            }
            $row++;
        }
        $this->columnCount = count($this->matrix[0]);

        $i = 0;
        $cycles = 1000000000;
        while ($i < $cycles) {
            $i = $this->cycleMatrix($i, $cycles);
        }
        dd($this->calcLoad());
    }

    private function cycleMatrix(int $current, int $max): int
    {
        if ($this->cycleLength && $current + $this->cycleLength < $max) {
            $rest = ($max - $current) % $this->cycleLength;
            return $max - $rest;
        }
        $this->moveRocks('N');
        $this->moveRocks('W');
        $this->moveRocks('S');
        $this->moveRocks('E');
        $this->checkCycle($current);
        return ++$current;
    }

    private function moveRocks(string $direction): void
    {
        switch ($direction) {
            case 'N':
                $strings = $this->getColumnStrings();
                $strings = array_map([$this, 'solveString'], $strings);
                $this->writeColumns($strings);
                break;
            case 'W':
                $strings = $this->getRowStrings();
                $strings = array_map([$this, 'solveString'], $strings);
                $this->writeRows($strings);
                break;
            case 'S':
                $strings = $this->getColumnStrings(true);
                $strings = array_map([$this, 'solveString'], $strings);
                $this->writeColumns($strings, true);
                break;
            case 'E':
                $strings = $this->getRowStrings(true);
                $strings = array_map([$this, 'solveString'], $strings);
                $this->writeRows($strings, true);
                break;
        }
    }

    private function solveString($string): string
    {
        $subStrings = explode('#', $string);
        $subStrings = array_map([$this, 'solveSubstring'], $subStrings);

        return implode('#', $subStrings);
    }

    private function solveSubstring($string): string
    {
        if ($solution = $this->subStringCache[$string] ?? null) {
            return $solution;
        }

        $length = strlen($string);

        $rockCount = substr_count($string, 'O');
        $solution = str_repeat('O', $rockCount) . str_repeat('.', $length - $rockCount);
        $this->subStringCache[$string] = $solution;

        return $solution;
    }

    private function getColumnStrings(bool $reverse = false): array
    {
        $columns = [];
        for ($i = 0; $i < $this->columnCount; $i++) {
            $column = array_column($this->matrix, $i);
            if ($reverse) {
                $column = array_reverse($column);
            }
            $columns[] = implode($column);
        }

        return $columns;
    }

    private function getRowStrings(bool $reverse = false): array
    {
        if ($reverse) {
            return array_map(fn ($row) => implode(array_reverse($row)), $this->matrix);
        }

        return array_map('implode', $this->matrix);
    }

    private function writeColumns(array $columns, bool $reverse = false): void
    {
        foreach ($columns as $columnIndex => $column) {
            if ($reverse) {
                $column = strrev($column);
            }
            foreach (str_split($column) as $rowIndex => $char) {
                $this->matrix[$rowIndex][$columnIndex] = $char;
            }
        }
    }

    private function writeRows(array $rows, bool $reverse = false): void
    {
        foreach ($rows as $rowIndex => $row) {
            if ($reverse) {
                $row = strrev($row);
            }
            foreach (str_split($row) as $columnIndex => $char) {
                $this->matrix[$rowIndex][$columnIndex] = $char;
            }
        }
    }

    private function calcLoad(): int
    {
        $total = 0;
        foreach ($this->matrix as $rowIndex => $row) {
            foreach ($row as $char) {
                if ($char === 'O') {
                    $total += $this->columnCount - $rowIndex;
                }
            }
        }

        return $total;
    }

    private function checkCycle(int $current): void
    {
        $md5 = md5(implode(array_map('implode', $this->matrix)));
        if ($md5 === $this->cycleStartHash) {
            $this->cycleLength = $current - $this->cycleStart;
        }
        if (in_array($md5, $this->hashes)) {
            if (!isset($this->cycleStart)) {
                $this->cycleStartHash = $md5;
                $this->cycleStart = $current;
            }
        }
        $this->hashes[] = $md5;
    }
}

(new Run())->run();


