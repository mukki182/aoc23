<?php
$handle = fopen('input.txt', 'rb');
$total = 0;
while ($line = fgets($handle)) {
    preg_match_all('/\d/', $line, $matches);
    $matches = $matches[0];
    if (count($matches) === 1) {
        $first = $last = $matches[0];
    } else {
        $first = array_shift($matches);
        $last = array_pop($matches);
    }
    $total += (int)$first.$last;
}
echo $total;