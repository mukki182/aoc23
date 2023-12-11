<?php

$times = [61, 70, 90, 66];
$distances = [643, 1184, 1362, 1041];

$totalWins = 1;
foreach ($times as $index => $totalTime) {
    $winDistance = $distances[$index];
    $wins = 0;
    for ($chargeTime = 1; $chargeTime < $totalTime; $chargeTime++) {
        $distance = ($totalTime - $chargeTime) * $chargeTime;
        if ($distance > $winDistance) $wins++;
    }

    $totalWins *= $wins;
}
echo $totalWins;