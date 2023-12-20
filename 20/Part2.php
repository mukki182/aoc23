<?php

require '../vendor/autoload.php';

class Part2
{
    private array $startModules = [];
    private array $flipFlopModules = [];
    private array $conjunctionModules = [];
    private array $queue = [];
    private int $currentCycle = 0;
    private string $endModuleKey;
    private array $cycleLengths;

    public function run(bool $test = false): void
    {
        $this->readInput($test);
        $this->findCycles();
        echo array_product($this->cycleLengths);
    }

    private function findCycles(): void
    {
        $endModule = array_filter($this->conjunctionModules, static fn ($module) => in_array('rx', $module['next']));
        $this->endModuleKey = array_key_first($endModule);
        $lastInputs = array_keys(end($endModule)['state']);
        $this->cycleLengths = array_combine($lastInputs, array_fill(0, count($lastInputs), INF));

        while (count(array_filter($this->cycleLengths, static fn ($cycleLength) => $cycleLength === INF))) {
            $this->currentCycle++;
            $this->pressButton();
        }
    }

    private function pressButton(): void
    {
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
            if ($moduleKey === $this->endModuleKey && $highPulse) {
                $this->cycleLengths[$input] = min($this->currentCycle, $this->cycleLengths[$input]);
            }
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

        foreach ($this->flipFlopModules as $moduleKey => $module) {
            foreach ($module['next'] as $nextKey) {
                if (array_key_exists($nextKey, $this->conjunctionModules)) {
                    $this->conjunctionModules[$nextKey]['state'][$moduleKey] = false;
                }
            }
        }

        foreach ($this->conjunctionModules as $moduleKey => $module) {
            foreach ($module['next'] as $nextKey) {
                if (array_key_exists($nextKey, $this->conjunctionModules)) {
                    $this->conjunctionModules[$nextKey]['state'][$moduleKey] = false;
                }
            }
        }
    }

}

(new Part2())->run(false);
