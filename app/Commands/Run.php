<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\search;

final class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a script';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var array<string, string|string[]> $scripts */
        $scripts = config('devpro.scripts');

        $scriptName = $this->getScriptName($scripts);

        if (! array_key_exists($scriptName, $scripts)) {
            error("No script found with name: {$scriptName}");

            return;
        }

        foreach ((array) $scripts[$scriptName] as $script) {
            $script = str($script)->whenStartsWith('@', function (Stringable $s) {
                return $s->replaceFirst('@', 'devpro run ');
            })->prepend(base_path())->toString();

            $this->executeScript($script);
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    /**
     * @return array<string|null>
     */
    private function toCommandArray(string $command): array
    {
        return str_getcsv($command, ' ', '"');
    }

    private function detectKeypress(): string
    {
        // Switch terminal to raw mode
        system('stty -icanon -echo');

        // Read a single character from the input
        $char = fread(STDIN, 1);

        // Restore terminal to normal mode
        system('stty sane');

        return is_string($char) ? $char : '';
    }

    private function handleKeyboardActions(Process $process): void
    {
        $read = [STDIN];
        $write = null;
        $except = null;

        // Check for keypress
        if (stream_select($read, $write, $except, 0)) {
            $action = $this->detectKeypress();

            match ($action) {
                'r' => $this->restartProcess($process),
                'q' => $this->quiteProcess($process),
                'c' => $this->clearScreen(),
                default => $this->doNothing(),
            };
        }
    }

    private function displayProcessOutput(Process $process): void
    {
        while ($process->isRunning() && ($buffer = $process->getIncrementalOutput())) {
            info($buffer);
        }
    }

    private function restartProcess(Process $process): void
    {
        info('Restarting...');
        $process->stop();
        $process->start();
    }

    private function quiteProcess(Process $process): void
    {
        info('Quitting...');
        $process->stop();
    }

    private function clearScreen(): void
    {
        info('Clearing screen...');
        clear();
    }

    private function executeScript(string $script): void
    {
        $command = $this->toCommandArray($script);
        $cwd = getcwd() ?: '.';

        $process = new Process($command, $cwd);
        $process->setTimeout(0); // Disable timeout for long-running commands
        $process->setTty(Process::isTtySupported()); // Enable TTY if supported

        $process->start();

        while ($process->isRunning()) {
            $this->handleKeyboardActions($process);
            $this->displayProcessOutput($process);
        }
    }

    /**
     * @param  array<string, string|string[]>  $scripts
     */
    private function getScriptName(array $scripts): string
    {
        $scriptName = $this->argument('name');

        if (is_null($this->argument('name'))) {
            $scriptName = $this->searchScript($scripts);
        }

        return (string) $scriptName;
    }

    /**
     * @param  array<string, string|string[]>  $scripts
     */
    private function searchScript(array $scripts): string
    {
        $script = search(
            label: 'Which script do you want to run?',
            options: fn (string $n) => collect($scripts)
                ->keys()
                ->filter(fn (string $script) => Str::contains($script, $n))
                ->values()
                ->all(),
            placeholder: 'Search for a script',
        );

        return (string) $script;
    }

    private function doNothing(): void
    {
        // Doing nothing
    }
}
