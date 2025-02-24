<?php

declare(strict_types=1);

namespace App\Commands;

use App\Support\Configuration;
use Illuminate\Console\Scheduling\Schedule;
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
        if (Configuration::exists()) {
            if (! confirm('Look like `devpulse` is already configured! Want to reconfigure it?')) {
                info('Configuration skipped!');

                return;
            }

            Configuration::delete();
        }

        Configuration::write();

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
