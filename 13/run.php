<?php

require '../vendor/autoload.php';

class Run
{
    private int $part1 = 0;
    private int $part2 = 0;

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
                $this->part1 += $pos * 100;
            }

            if ($pos = $this->findReflection($puzzle['columns'])) {
                $this->part1 += $pos;
            }
        }

        dump($this->part1);

        foreach ($puzzles as $puzzle) {
            if ($pos = $this->findReflectionWithDefect($puzzle['rows'])) {
                $this->part2 += $pos * 100;
            }

            if ($pos = $this->findReflectionWithDefect($puzzle['columns'])) {
                $this->part2 += $pos;
            }
        }

        dump($this->part2);
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

    private function findReflectionWithDefect(array $lines): int
    {
        for ($pos = 1, $max = count($lines); $pos < $max; $pos++) {
            $distance = 0;
            $first = array_slice($lines, 0, $pos);
            $second = array_reverse(array_slice($lines, $pos));
            for ($i = 0, $end = max(count($first), count($second)); $i < $end; $i++) {
                $nextF = array_pop($first);
                $nextS = array_pop($second);
                if ((null === $nextF || null === $nextS)) {
                    if ($distance === 1) {
                        return $pos;
                    }

                    break;
                }
                $distance += levenshtein($nextS, $nextF);
                if ($distance > 1) {
                    break;
                }
            }
        }

        return 0;
    }
}

(new Run())->run();


