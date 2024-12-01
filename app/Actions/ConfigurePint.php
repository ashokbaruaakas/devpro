<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

final class ConfigurePint
{
    private const systemConfigurationDir = '.devpro/default';

    private const configurationFile = 'pint.json';

    private string $applicationBasePath;

    private string $currentWorkingDirectory;

    public function __construct()
    {
        $this->applicationBasePath = base_path();
        $this->currentWorkingDirectory = getcwd();
    }

    public function __invoke(): void
    {
        if (! File::exists("{$this->currentWorkingDirectory}/vendor/bin/pint")) {
            $this->print('Pint not installed, installing it via composer...');
            exec('composer require laravel/pint --dev');
        }

        $targetedFilePath = "{$this->currentWorkingDirectory}/pint.json";

        if (File::exists($targetedFilePath) &&
            ! confirm('Do you want to overwrite the existing pint configuration file?', false)) {
            $this->print('Pint configuration skipped.');

            return;
        }

        File::copy($this->pintConfigurationFilePath(), $targetedFilePath);
        $this->print('Pint successfully configured.');
    }

    private function pintConfigurationFilePath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->applicationBasePath,
            self::systemConfigurationDir,
            self::configurationFile,
        ]);
    }

    private function print(string $message): void
    {
        if (! empty($message) && app()->runningInConsole()) {
            info($message);
        }
    }
}
