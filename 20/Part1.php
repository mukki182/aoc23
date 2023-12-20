<?php

require '../vendor/autoload.php';

class Part1
{
    private array $startModules = [];
    private array $flipFlopModules = [];
    private array $conjunctionModules = [];
    private array $queue = [];
    private int $countHighPulse = 0;
    private int $countLowPulse = 0;

    public function run(bool $test = false): void
    {
        $this->readInput($test);
        for ($i = 1; $i <= 1000; $i++) {
            $this->pressButton();
        }

        echo 'total: ' . $this->countHighPulse * $this->countLowPulse;
    }

    private function pressButton(): void
    {
        $this->countLowPulse++;
        foreach ($this->startModules as $moduleKey) {
            $this->queue[] = [$moduleKey, false, ''];
        }
        while ($nextPulse = array_shift($this->queue)) {
            [$moduleKey, $highPulse, $input] = $nextPulse;
            $this->sendPulse($moduleKey, $highPulse, $input);
        }
    }

    private function sendPulse(string $moduleKey, bool $highPulse, string $input): void
    {
        $highPulse ? $this->countHighPulse++ : $this->countLowPulse++;

        if (array_key_exists($moduleKey, $this->flipFlopModules)) {
            if ($highPulse) {
                return;
            }

            $this->flipFlopModules[$moduleKey]['state'] = !$this->flipFlopModules[$moduleKey]['state'];
            foreach ($this->flipFlopModules[$moduleKey]['next'] as $nextKey) {
                $this->queue[] = [$nextKey, $this->flipFlopModules[$moduleKey]['state'], $moduleKey];
            }

            return;
        }

        if (array_key_exists($moduleKey, $this->conjunctionModules)) {
            $this->conjunctionModules[$moduleKey]['state'][$input] = $highPulse;
            $nextHighPulse = !(count(array_filter($this->conjunctionModules[$moduleKey]['state'])) === count($this->conjunctionModules[$moduleKey]['state']));
            foreach ($this->conjunctionModules[$moduleKey]['next'] as $nextKey) {
                $this->queue[] = [$nextKey, $nextHighPulse, $moduleKey];
            }
        }
    }

    private function readInput(bool $test): void
    {
        $fileName = $test ? 'example2.txt' : 'input.txt';
        $handle = fopen($fileName, 'rb');
        while (false !== $line = fgets($handle)) {
            $line = trim($line);
            [$module, $output] = explode(' -> ', $line);
            switch ($module) {
                case 'broadcaster':
                    $this->startModules = explode(', ', $output);
                    break;
                case str_starts_with($module, '%'):
                    $this->flipFlopModules[substr($module, 1)] = [
                        'state' => false,
                        'next' => explode(', ', $output)
                    ];
                    break;
                case str_starts_with($module, '&'):
                    $this->conjunctionModules[substr($module, 1)] = [
                        'next' => explode(', ', $output)
                    ];
                    break;
            }
        }
        foreach ($this->flipFlopModules as $flipFlopModuleKey => $flipFlopModule) {
            foreach ($flipFlopModule['next'] as $nextKey) {
                if (array_key_exists($nextKey, $this->conjunctionModules)) {
                    $this->conjunctionModules[$nextKey]['state'][$flipFlopModuleKey] = false;
                }
            }
        }
    }

}

(new Part1())->run(false);
