<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

final class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the devpulse configurations';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $configName = config('devpulse.json_configuration_name');
        $configPath = absolute_path($configName);

        if (file_exists($configPath)) {
            if (! confirm('Look like devpulse is already configured! Want to reconfigure it?')) {
                return;
            }

            unlink($configPath);
        }

        $configContent = collect([
            'scripts' => [
                // TODO:: Add Some Default Scripts
            ],
        ]);

        File::put($configPath, $configContent->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        info('Configuration successfully finished!');
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
