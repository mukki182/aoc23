<?php

$cardMap = [
    'A' => 'A',
    'K' => 'B',
    'Q' => 'C',
    'J' => 'D',
    'T' => 'E',
    '9' => 'F',
    '8' => 'G',
    '7' => 'H',
    '6' => 'I',
    '5' => 'J',
    '4' => 'K',
    '3' => 'L',
    '2' => 'M'
];

$reverseCardMap = array_flip($cardMap);

$handle = fopen('input.txt', 'rb');
$cardBets = [];
while ($line = fgets($handle)) {
    [$cards, $bet] = explode(' ', $line);
    $cards = str_split($cards);
    $cards = array_map(fn ($card) => $cardMap[$card], $cards);

    $cardBets[implode($cards)] = (int)$bet;
}

$rankings = [];
foreach (array_keys($cardBets) as $cards) {
    $sortedCards = str_split($cards);
    sort($sortedCards, SORT_NATURAL);
    $sortedCards = implode($sortedCards);
    switch (true) {
        case preg_match('/(.)\1{4}/', $sortedCards):
            $rankings[0][] = $cards;
            break;
        case preg_match('/(.)\1{3}/', $sortedCards):
            $rankings[1][] = $cards;
            break;
        case preg_match('/(.)\1{2}(.)\2{1}/', $sortedCards) || preg_match('/(.)\1{1}(.)\2{2}/', $sortedCards) :
            $rankings[2][] = $cards;
            break;
        case preg_match('/(.)\1{2}/', $sortedCards):
            $rankings[3][] = $cards;
            break;
        case preg_match('/(.)\1{1}.?(.)\2{1}/', $sortedCards):
            $rankings[4][] = $cards;
            break;
        case preg_match('/(.)\1{1}/', $sortedCards):
            $rankings[5][] = $cards;
            break;
        default:
            $rankings[6][] = $cards;
            break;
    }
}

ksort($rankings);
$rankings = array_reverse($rankings);
foreach ($rankings as $index => $ranking) {
    sort($ranking,  SORT_NATURAL);
    $ranking = array_reverse($ranking);
    $rankings[$index] = $ranking;
}

$total = 0;
$rank = 1;
foreach ($rankings as $ranking) {
    foreach ($ranking as $cards) {
        $reverseMapCards = str_split($cards);
        $reverseMapCards = array_map(fn ($card) => $reverseCardMap[$card], $reverseMapCards);
        echo implode($reverseMapCards).': rank:'.$rank.' bet:'.$cardBets[$cards].PHP_EOL;
        $total += $rank++*$cardBets[$cards];
    }
}

echo $total;