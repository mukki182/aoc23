<?php

require '../vendor/autoload.php';

$handle = fopen('input.txt', 'rb');

$total = 0;
global $cache;
$cache = [];
while ($line = fgets($handle)) {
    $line = trim($line);
    [$input, $numbers] = explode(' ', $line);
    $input = implode('?', array_fill(0, 5, $input));
    $numbers = array_merge(...array_fill(0, 5, explode(',', $numbers)));

    $total += countSolutions($input, $numbers);
}

var_dump($total);

function countSolutions(string $input, array $numbers): int
{
    if (empty($input)) {
        return empty($numbers) ? 1 : 0;
    }

    if (empty($numbers)) {
        return str_contains($input, '#') ? 0 : 1;
    }

    global $cache;

    $key = $input . implode(',', $numbers);

    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $result = 0;

    $firstChar = $input[0];
    if (in_array($firstChar, ['.', '?'])) {
        $result += countSolutions(substr($input, 1), $numbers);
    }

    $nextNumber = array_shift($numbers);

    if (in_array($firstChar, ['?', '#'])) {
        if ($nextNumber <= strlen($input)
            && !str_contains(substr($input, 0, $nextNumber), '.')
            && ($nextNumber === strlen($input) || ($input[$nextNumber] ?? null) !== '#')
        ) {
            $result += countSolutions(substr($input, $nextNumber + 1), $numbers);
        }
    }

    $cache[$key] = $result;

    return $result;
}