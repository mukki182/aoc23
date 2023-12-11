<?php

$handle = fopen('input.txt', 'rb');
$total = 0;
$cardWins = [];
$i = 1;
while ($line = fgets($handle)) {
    list(, $numbersRaw) = explode(':', $line);
    list($winingRaw, $numbersRaw) = explode('|', $numbersRaw);
    $winingRaw = trim($winingRaw);
    $numbersRaw = trim($numbersRaw);
    $winingNumbers = preg_split('/\s+/', $winingRaw);
    $numbers = preg_split('/\s+/', $numbersRaw);
    $points = 0;
    foreach ($numbers as $number) {
        if (in_array($number, $winingNumbers)) {
            $points++;
        }
    }
    $cardWins[$i] = $points;
    $i++;
}
$copies = array_fill(1, count($cardWins), 1);
for ($index = 1, $count = count($cardWins); $index <= $count; $index++) {
    $points = $cardWins[$index];
    for ($i = $index + 1; $i <= $index + $points; $i++) {
        if (array_key_exists($i, $copies)) {
            $copies[$i] += $copies[$index];
        }
    }
}
echo array_sum($copies);