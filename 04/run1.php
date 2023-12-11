<?php

$handle = fopen('input.txt', 'rb');
$total = 0;
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
            $points = $points ? $points * 2 : 1;
        }
    }
    $total += $points;
}
echo $total;