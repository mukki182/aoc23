<?php

require '../vendor/autoload.php';

class Part1
{
    private array $input;

    public function run(bool $test = false): void
    {
        $this->readInput($test);

        $total = 0;
        foreach ($this->input as $string) {
            $total += $this->hashString($string);
        }

        echo $total;
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example.txt' : 'input.txt';
        $input = file_get_contents($fileName);
        $this->input = explode(',', $input);
    }

    private function hashString(string $string): int
    {
        $hashValue = 0;
        foreach (str_split($string) as $char) {
            $hashValue += ord($char);
            $hashValue *= 17;
            $hashValue %= 256;
        }

        return $hashValue;
    }
}

(new Part1())->run(false);
