<?php

$handle = fopen('example.txt', 'rb');

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

$emptyRow = array_fill(0, $columnCount, false);

for ($rowIndex = count($rowsHaveGalaxy) - 1; $rowIndex >= 0; $rowIndex--) {
    if (!$rowsHaveGalaxy[$rowIndex]) {
        array_splice($map, $rowIndex, 1, [$emptyRow, $emptyRow]);
    }
}

$rowIndexMax = count($map);

for ($columIndex = count($columnsHaveGalaxy) - 1; $columIndex >= 0; $columIndex--) {
    if (!$columnsHaveGalaxy[$columIndex]) {
        for ($rowIndex = 0; $rowIndex < $rowIndexMax; $rowIndex++) {
            array_splice($map[$rowIndex], $columIndex, 1, [false, false]);
        }
    }
}

#debug map
//foreach ($map as $row) {
//    foreach ($row as $column) {
//        echo $column ? '#' : '.';
//    }
//    echo PHP_EOL;
//}

$galaxies = [];

foreach ($map as $rIndex => $row) {
    foreach ($row as $cIndex => $column) {
        if ($column) {
            $galaxies [] = $rIndex . '|' . $cIndex;
        }
    }
}

$total = 0;
while ($galaxyA = array_pop($galaxies)) {
    list($x1, $y1) = explode('|', $galaxyA);
    foreach ($galaxies as $galaxyB) {
        list($x2, $y2) = explode('|', $galaxyB);
        $pathLength = abs($x1 - $x2) + abs($y1 - $y2);
        $total += abs($x1 - $x2) + abs($y1 - $y2);
    }
}

echo $total;
