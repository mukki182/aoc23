<?php

require '../vendor/autoload.php';

class Part2
{
    private array $rules = [];
    private array $ranges = [];

    public function run(bool $test = false): void
    {
        $this->readInput($test);
        $this->buildRanges(
            'in',
            array_combine(str_split('xmas'), array_fill(0, 4, [1, 4000])),
            'in'
        );
        $total = 0;
        foreach ($this->ranges as $range) {
            $solutions = 1;
            foreach ($range as $part) {
                $solutions *= ($part[1] - $part[0] + 1);
            }
            $total += $solutions;
        }
        echo $total . PHP_EOL;
    }

    private function buildRanges(string $nextKey, array $ranges, string $prevPath): void
    {
        if ($nextKey === 'R') {
            return;
        }

        if ($nextKey === 'A') {
            $this->ranges[] = $ranges;

            return;
        }

        $reverseRanges = $ranges;

        foreach ($this->rules[$nextKey] as $rule) {
            ['char' => $char, 'range' => $range, 'next' => $next] = $rule;

            $nextRange = $reverseRanges;

            if ($char !== null) {
                $nextRange[$char] = [max($nextRange[$char][0], $range[0]), min($nextRange[$char][1], $range[1])];
            }

            $this->buildRanges($next, $nextRange, $prevPath.'|'.$next);

            if ($char !== null) {
                if ($range[1] === 4000) {
                    $reverseRanges[$char][1] = min($reverseRanges[$char][1], $range[0] - 1);
                } else {
                    $reverseRanges[$char][0] = max($reverseRanges[$char][0], $range[1] +1);
                }
            }
        }
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        while (false !== $line = fgets($handle)) {
            $line = trim($line);
            if (empty($line)) {
                break;
            }

            $this->parseRule($line);
        }
    }

    private function parseRule(string $line): void
    {
        #px{a<2006:qkq,m>2090:A,rfg}
        [$key, $rest] = explode('{', $line);
        $rest = substr($rest, 0, -1);
        $ruleStrings = explode(',', $rest);
        $rules = [];
        foreach ($ruleStrings as $ruleString) {
            if (!str_contains($ruleString, ':')) {
                $rules[] = [
                    'char' => null,
                    'range' => null,
                    'next' => $ruleString
                ];
                continue;
            }

            [$conditionString, $next] = explode(':', $ruleString);
            preg_match('/(.)([<>])(.*)/', $conditionString, $matches);
            $rules[] = [
                'char' => $matches[1],
                'range' => $matches[2] === '>' ? [(int)$matches[3] + 1, 4000] : [1, (int)$matches[3] - 1],
                'next' => $next
            ];
        }
        $this->rules[$key] = $rules;
    }
}

(new Part2())->run(false);
