<?php

$handle = fopen('input.txt', 'rb');
$total = 0;
while ($line = fgets($handle)) {
    list($game, $draws) = explode(':', $line);
    list($g, $id) = explode(' ', $game);
    $draws = explode(';', $draws);
    $possible = true;
    foreach ($draws as $draw) {
        $cubes = explode(',', $draw);
        foreach ($cubes as $cube) {
            $count = (int)preg_replace('/\D/', '', $cube);
            $color = preg_replace('/[^a-z]/', '', $cube);
            switch ($color) {
                case 'red':
                    if ($count > 12) {
                        $possible = false;
                        continue 2;
                    }
                case 'green':
                    if ($count > 13) {
                        $possible = false;
                        continue 2;
                    }
                case 'blue':
                    if ($count > 14) {
                        $possible = false;
                        continue 2;
                    }
            }
        }
    }
    if ($possible) {
        $total += $id;
    }
}
echo $total;