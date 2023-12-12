<?php

$handle = fopen('input.txt', 'rb');
$seeds = explode(' ', str_replace('seeds: ', '', trim(fgets($handle))));
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
        'start' => (int)$values[1],
        'end' => $values[1] + $values[2] - 1,
        'offset' => $values[0] - $values[1]
    ];
}
foreach ($maps as $index => &$ranges) {
    usort($ranges, fn ($a, $b) => $a['start'] <=> $b['start']);
}
unset($ranges);

$lowestLocation = null;
foreach ($seeds as $seed) {
    echo 'seed:' . $seed . PHP_EOL;
    foreach ($maps as $ranges) {
        foreach ($ranges as $range) {
            if ($seed >= $range['start'] && $seed <= $range['end']) {
                echo sprintf(
                    'found seed %s in range %s - %s with offset %s. next seed %s'.PHP_EOL,
                    $seed,
                    $range['start'],
                    $range['end'],
                    $range['offset'],
                    $seed += $range['offset']
                );
                break;
            }
        }
    }
    if (!$lowestLocation || $seed < $lowestLocation) {
        $lowestLocation = $seed;
    }
}

echo $lowestLocation;