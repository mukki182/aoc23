<?php

$handle = fopen('input.txt', 'rb');
$histories = [];

while ($line = fgets($handle)) {
    $line = trim($line);
    $histories [] = explode(' ', $line);
}

$total = 0;

foreach ($histories as $history) {
    $rounds = [$history];
    while (true) {
        $newHistory = [];
        $notNull = false;
        for ($i = 0, $count = count($history) - 1; $i < $count; $i++) {
            $nextValue = $history[$i + 1] - $history[$i];
            $newHistory[] = $nextValue;
            if ($nextValue !== 0) {
                $notNull = true;
            }
        }
        if ($notNull) {
            $history = $newHistory;
            $rounds[] = $newHistory;
        } else {
            break;
        }
    }

    $interpol = 0;

    for ($i = count($rounds) - 1; $i >= 0; $i--) {
        $interpol += end($rounds[$i]);
    }

    $total += $interpol;
}

echo $total;