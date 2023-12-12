<?php

class Run
{
    private array $maps;
    private int $total = 0;

    public function run()
    {
        $handle = fopen('input.txt', 'rb');

        $records = [];
        while ($line = fgets($handle)) {
            $line = trim($line);
            $records[] = explode(' ', $line);
        }
        foreach ($records as $record) {
            [$questionMarkMap, $solution] = $record;

            $this->maps = [];
            $this->resolveQuestionsMarks($questionMarkMap);
            foreach ($this->maps as $map) {
                if ($this->mapToSolution($map) === $solution) {
                    $this->total++;
                }
            }
        }

        echo $this->total;
    }

    private function resolveQuestionsMarks(string $string): void
    {
        if (!str_contains($string, '?')) {
            $this->maps [] = $string;

            return;
        }

        $pos = strpos($string, '?');
        $this->resolveQuestionsMarks(substr_replace($string, '.', $pos, 1));
        $this->resolveQuestionsMarks(substr_replace($string, '#', $pos, 1));
    }

    private function mapToSolution(string $map): string
    {
        $shortedMap = preg_replace('/\.+/', '.', $map);
        $blocks = explode('.', $shortedMap);
        $blocks = array_filter($blocks);
        return implode(',', array_map(static fn($item) => strlen($item), $blocks));
    }

}

(new Run())->run();


