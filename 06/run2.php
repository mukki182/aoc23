<?php

$times = [61709066];
$distances = [643118413621041];

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