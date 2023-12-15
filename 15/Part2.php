<?php

require '../vendor/autoload.php';

class Part2
{
    private array $input;
    private array $boxes = [];
    private array $cache = [];

    public function run(bool $test = false): void
    {
        $this->readInput($test);

        $this->boxes = array_fill(0, 256, []);

        foreach ($this->input as $string) {
            [$label, $operation, $focalLength] = $this->extractTask($string);
            $boxIndex = $this->hashString($label);
            switch ($operation) {
                case '-':
                    $this->removeLensFromBox($label, $boxIndex);
                    break;
                case '=':
                    $this->upsertLabelToBox($label, $focalLength, $boxIndex);
                    break;
            }
        }

        echo $this->calcTotal();
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $input = file_get_contents($fileName);
        $this->input = explode(',', $input);
    }

    private function hashString(string $string): int
    {
        if (array_key_exists($string, $this->cache)) {
            return $this->cache[$string];
        }

        $hashValue = 0;
        foreach (str_split($string) as $char) {
            $hashValue += ord($char);
            $hashValue *= 17;
            $hashValue %= 256;
        }

        $this->cache[$string] = $hashValue;

        return $hashValue;
    }

    private function extractTask(string $string): array
    {
        if (str_ends_with($string, '-')) {
            return [
                substr($string, 0, -1),
                '-',
                null
            ];
        }

        $pos = strpos($string, '=');

        return [
            substr($string, 0, $pos),
            '=',
            (int)substr($string, $pos + 1)
        ];
    }

    private function removeLensFromBox(string $label, int $boxIndex): void
    {
        if (array_key_exists($label, $this->boxes[$boxIndex])) {
            unset($this->boxes[$boxIndex][$label]);
        }
    }

    private function upsertLabelToBox(string $label, int $focalLength, int $boxIndex): void
    {
        if (array_key_exists($label, $this->boxes[$boxIndex])) {
            $this->boxes[$boxIndex][$label] = $focalLength;
        } else {
            $this->boxes[$boxIndex] += [$label => $focalLength];
        }
    }

    private function calcTotal(): int
    {
        $total = 0;
        foreach ($this->boxes as $boxIndex => $box) {
            foreach (array_values($box) as $lensIndex => $focalLength) {
                $total += ($boxIndex + 1) * ($lensIndex + 1) * $focalLength;
            }
        }

        return $total;
    }
}

(new Part2())->run(false);
