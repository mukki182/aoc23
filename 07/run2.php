<?php

$cardMap = [
    'A' => 'A',
    'K' => 'B',
    'Q' => 'C',
    'T' => 'E',
    '9' => 'F',
    '8' => 'G',
    '7' => 'H',
    '6' => 'I',
    '5' => 'J',
    '4' => 'K',
    '3' => 'L',
    '2' => 'M',
    'J' => 'X'
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
    echo $sortedCards.':';
    switch (true) {
        case preg_match('/(.)\1{4}/', $sortedCards)
            || preg_match('/([^X])\1{3}X/', $sortedCards)
            || preg_match('/([^X])\1{2}XX/', $sortedCards)
            || preg_match('/([^X])\1{1}XXX/', $sortedCards)
            || preg_match('/XXXX/', $sortedCards):
            $rankings[0][] = $cards;
            echo '5'.PHP_EOL;
            break;
        case preg_match('/(.)\1{3}/', $sortedCards)
            || preg_match('/([^X])\1{2}.*X/', $sortedCards)
            || preg_match('/([^X])\1{1}.*XX/', $sortedCards)
            || preg_match('/XXX/', $sortedCards):
            $rankings[1][] = $cards;
            echo '4'.PHP_EOL;
            break;
        case preg_match('/(.)\1{2}(.)\2{1}/', $sortedCards)
            || preg_match('/(.)\1{1}(.)\2{2}/', $sortedCards)
            || preg_match('/([^X])\1{1}([^X])\2{1}X/', $sortedCards):
            $rankings[2][] = $cards;
            echo 'FH'.PHP_EOL;
            break;
        case preg_match('/(.)\1{2}/', $sortedCards)
            || preg_match('/([^X])\1{1}.*X/', $sortedCards)
            || preg_match('/XX/', $sortedCards):
            $rankings[3][] = $cards;
            echo '3'.PHP_EOL;
            break;
        case preg_match('/(.)\1{1}.?(.)\2{1}/', $sortedCards):
            $rankings[4][] = $cards;
            echo '2P'.PHP_EOL;
            break;
        case preg_match('/(.)\1{1}/', $sortedCards)
            || preg_match('/X/', $sortedCards):
            $rankings[5][] = $cards;
            echo '1P'.PHP_EOL;
            break;
        default:
            $rankings[6][] = $cards;
            echo 'H'.PHP_EOL;
            break;
    }
}

ksort($rankings);
$rankings = array_reverse($rankings);
foreach ($rankings as $index => $ranking) {
    sort($ranking, SORT_NATURAL);
    $ranking = array_reverse($ranking);
    $rankings[$index] = $ranking;
}

$total = 0;
$rank = 1;
foreach ($rankings as $ranking) {
    foreach ($ranking as $cards) {
        $reverseMapCards = str_split($cards);
        $reverseMapCards = array_map(fn ($card) => $reverseCardMap[$card], $reverseMapCards);
        echo implode($reverseMapCards) . ': rank:' . $rank . ' bet:' . $cardBets[$cards] . PHP_EOL;
        $total += $rank++ * $cardBets[$cards];
    }
}

echo $total;