<?php

declare(strict_types=1);

namespace App\Commands;

use App\Actions\ConfigurePint;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\multiselect;

final class Configure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configure {--tools=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure Packages or Tools';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tools = $this->getTools();

        if (empty($tools)) {
            $this->info('Nothing to configure.');

            return;
        }

        collect($tools)->each(fn (string $tool) => (new $tool())());
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function getTools(): array
    {
        $supportedTools = [ConfigurePint::class => 'Pint'];

        if ($toolsStr = $this->option('tools')) {
            $tools = explode(',', $toolsStr);
            $tools = array_keys(array_diff($supportedTools, $tools));
        }

        if (! isset($tools)) {
            $tools = multiselect(
                label: 'What do you want to configure?',
                options: $supportedTools
            );
        }

        return $tools;
    }
}
