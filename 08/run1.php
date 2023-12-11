<?php

$handle = fopen('input.txt', 'rb');
$map = [];
while ($line = fgets($handle)) {
    $line = preg_replace('/\s|\(|\)/', '', $line);
    [$index, $pair] = explode('=', $line);
    $map[$index] = explode(',', $pair);
}

$leftRight = str_split(file_get_contents('leftright.txt'));
$leftRightCount = count($leftRight);
$leftRightPointer = 0;
$index = 'AAA';

$steps = 0;
while (true) {
    $index = $leftRight[$leftRightPointer] === 'L' ? $map[$index][0] : $map[$index][1];
    $steps++;
    $leftRightPointer++;
    if ($index === 'ZZZ') {
        break;
    }
    if ($leftRightPointer === $leftRightCount) {
        $leftRightPointer = 0;
    }
}
echo $steps;