<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;
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

    private string $applicationBasePath;

    private string $currentWorkingDirectory;

    private string $defaultConfigurationDirectory = '.devpro/default';

    /** @var array{string: string} */
    private array $defaultConfigurationFiles = [
        'pint' => 'pint.json',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->applicationBasePath = base_path();
        $this->currentWorkingDirectory = getcwd();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tools = $this->getTools();

        if (empty($tools)) {
            $this->info('Nothing to configure.');

            return;
        }

        when(in_array('Pint', $tools), fn () => $this->configurePint());
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
        $supportedTools = ['Pint'];

        if ($toolsStr = $this->option('tools')) {
            $tools = explode(',', $toolsStr);
            $tools = array_diff($supportedTools, $tools);
        }

        if (! isset($tools)) {
            $tools = multiselect(
                label: 'What do you want to configure?',
                options: ['Pint']
            );
        }

        return $tools;
    }

    private function configurePint(): void
    {
        if (! File::exists($this->currentWorkingDirectory.'/vendor/bin/pint')) {
            $this->info('Pint not installed, installing it via composer...');

            exec('composer require laravel/pint --dev');
        }

        $targetedFilePath = $this->currentWorkingDirectory.'/pint.json';

        if (File::exists($targetedFilePath)) {
            $this->info('Pint already configured.');

            if (
                ! confirm('Do you want to overwrite the existing pint configuration file?', false)
            ) {

                $this->info('Pint configuration skipped.');

                return;
            }

            File::delete($targetedFilePath);
        }

        File::copy($this->pintConfigurationFilePath(), $targetedFilePath);

        $this->info('Pint successfully configured.');
    }

    private function pintConfigurationFilePath(): string
    {
        return $this->applicationBasePath
        .DIRECTORY_SEPARATOR
        .$this->defaultConfigurationDirectory
        .DIRECTORY_SEPARATOR
        .$this->defaultConfigurationFiles['pint'];
    }
}
