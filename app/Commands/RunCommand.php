<?php

declare(strict_types=1);

namespace App\Commands;

use App\Actions\RunScript;
use App\Support\Configuration;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Terminal;

use function Termwind\render;

/**
 * @phpstan-import-type ScriptShape from Configuration
 */
final class RunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run {names}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run predefined shell scripts';

    /**
     * Execute the console command.
     */
    public function handle(Terminal $terminal, Configuration $configuration, RunScript $runScript): void
    {
        /** @var string[] $names */
        $names = str($this->argument('names'))->explode(',')->toArray();

        $scripts = $configuration->scripts(...$names);

        foreach ($scripts as $script) {
            $this->scriptHeading($script, $terminal->getWidth());
            $runScript->handle($script);
            $this->newLine();
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
     * Prints a formatted script title.
     *
     * @param  array<key-of<ScriptShape>, value-of<ScriptShape>>  $script
     */
    private function scriptHeading(array $script, int $terminalWidth): void
    {
        $title = "Running: {$script['name']} `{$script['command']}` ";
        $dots = str_repeat('.', max($terminalWidth - mb_strlen($title) - 1, 0));

        render(<<<HTML
            <div>
                <span class="mx-1">$title</span>
                <span class="text-gray-500">$dots</span>
            </div>
        HTML);
    }
}
