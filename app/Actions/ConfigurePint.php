<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Invokable;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

final class ConfigurePint implements Invokable
{
    private string $cwd;

    public function __construct()
    {
        $this->cwd = getcwd();
    }

    public function __invoke(): void
    {
        info('Configuring Pint...');

        if (! File::exists("{$this->cwd}/vendor/bin/pint")) {
            info('Pint not installed, installing it via composer...');
            exec('composer require laravel/pint --dev');
        }

        $targetedPath = "{$this->cwd}/pint.json";

        if (File::exists($targetedPath) &&
            ! confirm('Do you want to overwrite the existing pint configuration file?', false)) {
            info('Pint configuration skipped.');

            return;
        }

        File::copy($this->pintStubPath(), $targetedPath);
        info('Pint successfully configured.');
    }

    private function pintStubPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('devpro.stubs_path'),
            'pint-json.stub',
        ]);
    }
}
