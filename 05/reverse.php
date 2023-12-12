<?php

$handle = fopen('input.txt', 'rb');
$seedPairs = explode(' ', str_replace('seeds: ', '', trim(fgets($handle))));

$seeds = [];
for ($i = 0, $iMax = count($seedPairs); $i < $iMax; $i += 2) {
    $seeds[] = [
        (int)$seedPairs[$i],
        $seedPairs[$i] + $seedPairs[$i + 1],
    ];
}

$maps = [];
$mapIndex = -1;
while ($line = fgets($handle)) {
    $line = trim($line);
    if (empty($line)) {
        continue;
    }
    if (str_contains($line, 'map')) {
        $mapIndex++;
        continue;
    }
    $values = explode(' ', $line);
    $maps[$mapIndex][] = [
        (int)$values[0],
        $values[0] + $values[2] - 1,
        $values[1] - $values[0]
    ];
}

$maps = array_reverse($maps);
$location = 1;
$mapCount = count($maps);
while ($location++) {
    $target = $location;
    for ($i = 0; $i < $mapCount; $i++) {
        foreach ($maps[$i] as $range) {
            [$start, $end, $offset] = $range;
            if ($target >= $start && $target <= $end) {
                $target += $offset;
                break;
            }
        }
    }
    foreach ($seeds as $seed) {
        [$min, $max] = $seed;
        if ($target >= $min && $target <= $max) {
            echo $location;
            die();
        }
    }
}