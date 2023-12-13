<?php

require '../vendor/autoload.php';

class Run2
{
    private int $total = 0;

    public function run()
    {
        $handle = fopen('input.txt', 'rb');

        $puzzles = [];
        $i = 0;
        while ($line = fgets($handle)) {
            $line = trim($line);
            if (empty($line)) {
                $i++;
                continue;
            }

            $puzzles[$i]['rows'][] = $line;
            foreach (str_split($line) as $pos => $char) {
                $puzzles[$i]['columns'][$pos][] = $char;
            }
        }

        foreach ($puzzles as &$puzzle) {
            $puzzle['columns'] = array_map(fn ($column) => implode($column), $puzzle['columns']);
        }
        unset($puzzle);

        foreach ($puzzles as $puzzle) {
            if ($pos = $this->findReflection($puzzle['rows'])) {
                dump($pos);
                $this->total += $pos * 100;
            }

            if ($pos = $this->findReflection($puzzle['columns'])) {
                dump($pos);
                $this->total += $pos;
            }

            dump($this->total);
        }
    }

    private function findReflection(array $lines): int
    {
        for ($pos = 1, $max = count($lines); $pos < $max; $pos++) {
            $first = array_slice($lines, 0, $pos);
            $firstString = implode(array_reverse($first));
            $secondString = implode(array_slice($lines, $pos));
            if (str_starts_with($firstString, $secondString) || str_starts_with($secondString, $firstString)) {
                return count($first);
            }
        }

        return 0;
    }
}

(new Run())->run();


