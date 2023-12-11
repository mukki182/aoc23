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

$nodes = [];
foreach (array_keys($map) as $node) {
    if (str_ends_with($node, 'A')) {
        echo $node . PHP_EOL;
        $index = $node;
        $steps = 0;
        $leftRightPointer = 0;
        while (true) {
            $index = $leftRight[$leftRightPointer] === 'L' ? $map[$index][0] : $map[$index][1];
            $steps++;
            $leftRightPointer++;
            if (str_ends_with($index, 'Z')) {
                break;
            }
            if ($leftRightPointer === $leftRightCount) {
                $leftRightPointer = 0;
            }
        }
        echo $steps . PHP_EOL;
        $nodes[] = $steps;
    }
}
$lcm = array_pop($nodes);
while ($next = array_pop($nodes)) {
    $lcm = gmp_lcm($lcm, $next);
}
echo $lcm;