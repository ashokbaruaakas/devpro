<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Invokable;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;

final class ConfigureLarastan implements Invokable
{
    private string $cwd;

    public function __construct()
    {
        $this->cwd = getcwd();
    }

    public function __invoke(): void
    {
        if (! File::exists("{$this->cwd}/vendor/bin/phpstan")) {
            info('Larastan not installed, installing it via composer...');
            exec('composer require --dev "larastan/larastan:^3.0"');
        }

        $targetPath = "{$this->cwd}/larastan.neon";

        if (File::exists($targetPath) &&
            ! confirm('Do you want to overwrite the existing larastan configuration file?', default: false)) {
            info('Larastan configuration skipped.');

            return;
        }

        File::copy($this->larastanStubPath(), $targetPath);
        info('Larastan successfully configured.');
    }

    private function larastanStubPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('devpro.stubs_path'),
            'larastan-neon.stub',
        ]);
    }
}
