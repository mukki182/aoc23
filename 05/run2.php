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

array_reverse($seeds);

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
        (int)$values[1],
        $values[1] + $values[2] - 1,
        $values[0] - $values[1]
    ];
}

foreach ($maps as $index => &$ranges) {
    usort($ranges, fn ($a, $b) => $a[0] <=> $b[0]);
}
unset($ranges);

for ($i = 0, $count = count($maps); $i < $count; $i++) {
    $nextSeeds = [];
    while ($seed = array_pop($seeds)) {
        [$seedStart, $seedEnd] = $seed;
        $foundSomething = false;
        foreach ($maps[$i] as $range) {
            [$rangeStart, $rangeEnd, $offset] = $range;
            //seed ends before next range, stop here
            if ($seedEnd < $rangeStart) {
                break;
            }
            //seed starts after range, go next
            if ($seedStart > $rangeEnd) {
                continue;
            }

            //seed starts before range, cut first block
            if ($seedStart < $rangeStart) {
                $nextSeeds[] = [
                    $seedStart,
                    $rangeStart - 1
                ];
                $seedStart = $rangeStart;
            }
            //seed ends before range end, finished here
            if ($seedEnd <= $rangeEnd) {
                $nextSeeds[] = [
                    $seedStart + $offset,
                    $seedEnd + $offset
                ];
                $foundSomething = true;
                break;
            }
            //seed ends after range end
            $nextSeeds[] = [
                $seedStart + $offset,
                $rangeEnd + $offset
            ];
            $foundSomething = true;
            $seeds[] = [
                $rangeEnd + 1,
                $seedEnd
            ];
        }
        //found nothing
        if (!$foundSomething) {
            $nextSeeds[] = $seed;
        }
    }
    $seeds = array_reverse($nextSeeds);
}

usort($seeds, fn ($a, $b) => $a[0] <=> $b[0]);
echo $seeds[0][0];