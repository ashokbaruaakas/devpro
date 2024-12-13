<?php

declare(strict_types=1);

namespace App\Commands;

use App\Actions\ConfigureLarastan;
use App\Actions\ConfigurePail;
use App\Actions\ConfigurePint;
use App\Actions\ConfigurePrettier;
use App\Contracts\Invokable;
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

        if (count($tools) === 0) {
            $this->info('Nothing to configure.');

            return;
        }

        collect($tools)->each(fn (string $tool) => $this->invokeAction(new $tool()));
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    /**
     * Get all the tools to configure.
     *
     * @return array<class-string<Invokable>>
     */
    private function getTools(): array
    {
        /** @var array<string, class-string<Invokable>> */
        $supportedTools = [
            'PINT' => ConfigurePint::class,
            'LARASTAN' => ConfigureLarastan::class,
            'PAIL' => ConfigurePail::class,
            'PRETTIER' => ConfigurePrettier::class,
        ];

        $tools = $this->option('tools')
            ? collect(explode(',', $this->option('tools')))
                ->map(fn (string $tool) => mb_strtoupper($tool))
                ->filter(fn (string $tool) => array_key_exists($tool, $supportedTools))
                ->map(fn (string $tool) => $supportedTools[$tool])
                ->toArray()
            : multiselect(
                label: 'What do you want to configure?',
                options: array_flip($supportedTools),
            );

        /** @var array<class-string<Invokable>> $tools */
        return $tools;
    }

    private function invokeAction(Invokable $action): void
    {
        $action();
    }
}
