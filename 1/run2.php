<?php

$handle = fopen('input.txt', 'rb');
$map = [
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4,
    'five' => 5,
    'six' => 6,
    'seven' => 7,
    'eight' => 8,
    'nine' => 9,
];
$total = 0;
while ($line = fgets($handle)) {
    preg_match_all('/(one|two|three|four|five|six|seven|eight|nine|\d)?/U', $line, $matches);
    var_dump($matches);
    $matches = $matches[0];
    if (count($matches) === 1) {
        $first = $last = $matches[0];
    } else {
        $first = array_shift($matches);
        $last = array_pop($matches);
    }
    if (strlen($first) > 1) {
        $first = $map[$first];
    }
    if (strlen($last) > 1) {
        $last= $map[$last];
    }
    var_dump($line,(int)($first.$last));
    $total += (int)($first . $last);
}
echo $total;