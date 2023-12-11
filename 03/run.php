<?php

$handle = fopen('input.txt', 'rb');
$total = $gearTotel = 0;
$lineIndex = 0;
global $symbolMap, $gearMap;
$symbolMap = $gearMap = [];
while ($line = fgets($handle)) {
    $line = trim($line);
    $chars = str_split($line);
    foreach ($chars as $charIndex => $char) {
        $symbolMap[$lineIndex][$charIndex] = $char;
    }
    $lineIndex++;
}

rewind($handle);
$lineIndex = 0;

while ($line = fgets($handle)) {
    $number = '';
    $line = trim($line);
    $chars = str_split($line);
    $start = $end = null;
    foreach ($chars as $charIndex => $char) {
        if (is_numeric($char)) {
            $number .= $char;
            if (is_null($start)) {
                $start = $charIndex;
            }
        }

        if ((strlen($number) > 0) && (!is_numeric($char))) {
            if (hasAdjacent($start, $charIndex - 1, $lineIndex, $number)) {
                $total += $number;
            }
            $number = '';
            $start = null;
        }

        if ($charIndex === 139 && (strlen($number) > 0) && hasAdjacent($start, $charIndex, $lineIndex, $number)) {
            $total += $number;
        }
    }
    $lineIndex++;
}

foreach ($gearMap as $gearRow) {
    foreach ($gearRow as $gearCell) {
        if (count($gearCell) === 2) {
            $gearTotel += $gearCell[0] * $gearCell[1];
        }
    }
}
echo $total.PHP_EOL;
echo $gearTotel.PHP_EOL;

function hasAdjacent(int $start, int $end, int $line, $number): bool
{
    global $symbolMap, $gearMap;
    $adjacent = false;
    for ($i = $start - 1; $i <= $end + 1; $i++) {
        if (array_key_exists($line - 1, $symbolMap) && array_key_exists($i, $symbolMap[$line - 1])) {
            if ($symbolMap[$line - 1][$i] !== '.') {
                $adjacent = true;
            }
            if ($symbolMap[$line - 1][$i] === '*') {
                $gearMap[$line - 1][$i][] = $number;
            }
        }
        if (array_key_exists($line + 1, $symbolMap) && array_key_exists($i, $symbolMap[$line + 1])) {
            if ($symbolMap[$line + 1][$i] !== '.') {
                $adjacent = true;
            }
            if ($symbolMap[$line + 1][$i] === '*') {
                $gearMap[$line + 1][$i][] = $number;
            }
        }
    }
    if (array_key_exists($start - 1, $symbolMap[$line])) {
        if ($symbolMap[$line][$start - 1] !== '.') {
            $adjacent = true;
        }
        if ($symbolMap[$line][$start - 1] === '*') {
            $gearMap[$line][$start - 1][] = $number;
        }
    }

    if (array_key_exists($end + 1, $symbolMap[$line])) {
        if ($symbolMap[$line][$end + 1] !== '.') {
            $adjacent = true;
        }
        if ($symbolMap[$line][$end + 1] === '*') {
            $gearMap[$line][$end + 1][] = $number;
        }
    }

    return $adjacent;
}
