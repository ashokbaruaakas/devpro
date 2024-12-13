<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Invokable;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\info;

final class ConfigurePail implements Invokable
{
    public function __invoke(): void
    {
        info('Configuring Pail...');

        $cwd = getcwd() ?: '.';

        if (! File::exists("{$cwd}/vendor/bin/pail")) {
            info('Pail not installed, installing it via composer...');
            exec('composer require --dev laravel/pail');
        }

        info('Pail successfully configured.');
    }

    private function pailStubPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('devpro.stubs_path'),
            'pail-neon.stub',
        ]);
    }
}
