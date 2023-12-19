<?php

require '../vendor/autoload.php';

class Part1
{
    private array $parts = [];
    private array $rules = [];

    public function run(bool $test = false): void
    {
        $this->readInput($test);
        $total = 0;
        foreach ($this->parts as $part) {
            $total += $this->checkPart($part);
        }

        echo $total.PHP_EOL;
    }

    private function checkPart(array $part): int
    {
        $nextRule = $this->rules['in'];
        while (!in_array($nextKey = $this->checkRule($nextRule, $part), ['A', 'R'])) {
            $nextRule = $this->rules[$nextKey];
        }

        return $nextKey === 'A' ? array_sum($part) : 0;
    }

    private function checkRule(array $rules, array $parts): string
    {
        foreach ($rules as $rule) {
            [$partKey, $checkValue, $next] = $rule;
            if ($partKey === null) {
                return $next;
            }

            if (($checkValue > 0 && $parts[$partKey] - $checkValue > 0) || ($checkValue < 0 && $checkValue + $parts[$partKey] < 0)) {
                return $next;
            }
        }

        return 'R';
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        $readRules = true;
        while (false !== $line = fgets($handle)) {
            $line = trim($line);
            if (empty($line)) {
                $readRules = false;
                continue;
            }

            if ($readRules) {
                $this->parseRule($line);
            } else {
                $this->parseParts($line);
            }
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
                    null,
                    0,
                    $ruleString
                ];
                continue;
            }

            [$conditionString, $next] = explode(':', $ruleString);
            preg_match('/(.)([<>])(.*)/', $conditionString, $matches);
            $rules[] = [
                $matches[1],
                $matches[2] === '>' ? (int)$matches[3] : -(int)$matches[3],
                $next
            ];
        }
        $this->rules[$key] = $rules;
    }

    private function parseParts(string $line): void
    {
        #{x=787,m=2655,a=1222,s=2876}
        $line = str_replace(['{', '}'], '', $line);
        $partValueStrings = explode(',', $line);
        $parts = [];
        foreach ($partValueStrings as $partValueString) {
            [$key, $value] = explode('=', $partValueString);
            $parts[$key] = $value;
        }
        $this->parts[] = $parts;
    }
}

(new Part1())->run(false);
