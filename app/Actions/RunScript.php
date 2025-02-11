<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\Configuration;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\note;

/**
 * @phpstan-import-type ScriptShape from Configuration
 */
final class RunScript
{
    /**
     * @param  ScriptShape  $script
     */
    public function handle(array $script): bool
    {
        return rescue(
            function () use ($script) {
                $command = $this->toCommandArray($script['command']);
                $path = absolute_path();

                $process = new Process($command, $path);
                $process->setTimeout(0); // Disable timeout for long-running commands TODO:: Make Me Configurable
                $process->setTty(Process::isTtySupported()); // Enable TTY if supported TODO:: Make Me Configurable

                $process->start();

                while ($process->isRunning()) {
                    $this->displayProcessOutput($process);
                }

                return true;
            },
            false
        );
    }

    /**
     * @return array<string|null>
     */
    private function toCommandArray(string $command): array
    {
        return str_getcsv($command, ' ');
    }

    private function displayProcessOutput(Process $process): void
    {
        while ($process->isRunning() && ($buffer = $process->getIncrementalOutput())) {
            note($buffer);
        }
    }
}
