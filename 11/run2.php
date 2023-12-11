<?php

$handle = fopen('input.txt', 'rb');

$lineIndex = 0;
$map = [];
while ($line = fgets($handle)) {
    $line = trim($line);
    foreach (str_split($line) as $index => $char) {
        $map[$lineIndex][$index] = $char === '#';
    }
    $lineIndex++;
}
$rowCount = count($map);
$columnCount = count($map[0]);

$columnsHaveGalaxy = array_fill(0, $columnCount, false);
$rowsHaveGalaxy = array_fill(0, $rowCount, false);

foreach ($map as $rowIndex => $row) {
    foreach ($row as $columnIndex => $column) {
        if ($column) {
            $columnsHaveGalaxy[$columnIndex] = true;
            $rowsHaveGalaxy[$rowIndex] = true;
        }
    }
}

$galaxies = [];

foreach ($map as $rIndex => $row) {
    foreach ($row as $cIndex => $column) {
        if ($column) {
            $galaxies [] = [$rIndex, $cIndex];
        }
    }
}

$total = 0;
while ($galaxyA = array_shift($galaxies)) {
    [$x1, $y1] = $galaxyA;
    foreach ($galaxies as $galaxyB) {
        $pathLength = 0;
        [$x2, $y2] = $galaxyB;
        if ($x1 !== $x2) {
            for ($x = min($x1, $x2), $maxX = max($x1, $x2); $x < $maxX; $x++) {
                $pathLength++;
                if (!$rowsHaveGalaxy[$x]) {
                    $pathLength += 1000000 - 1;
                }
            }
        }
        if ($y1 !== $y2) {
            for ($y = min($y1, $y2), $maxY = max($y1, $y2); $y < $maxY; $y++) {
                $pathLength++;
                if (!$columnsHaveGalaxy[$y]) {
                    $pathLength += 1000000 - 1;
                }
            }
        }
        $total += $pathLength;
    }
}

echo $total;
