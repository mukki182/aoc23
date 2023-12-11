<?php

$handle = fopen('input.txt', 'rb');
$total = 0;
while ($line = fgets($handle)) {
    list($game, $draws) = explode(':', $line);
    list($g, $id) = explode(' ', $game);
    $draws = explode(';', $draws);
    $red = $green = $blue = 0;
    foreach ($draws as $draw) {
        $cubes = explode(',', $draw);
        foreach ($cubes as $cube) {
            $count = (int)preg_replace('/\D/', '', $cube);
            $color = preg_replace('/[^a-z]/', '', $cube);
            switch ($color) {
                case 'red':
                    if ($count > $red) {
                        $red = $count;
                    }
                    break;
                case 'green':
                    if ($count > $green) {
                        $green = $count;
                    }
                    break;
                case 'blue':
                    if ($count > $blue) {
                        $blue = $count;
                    }
                    break;
            }
        }
    }
    $total += ($red*$green*$blue);
}
echo $total;