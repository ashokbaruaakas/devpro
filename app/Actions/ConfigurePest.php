<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Invokable;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

final class ConfigurePest implements Invokable
{
    public function __invoke(): void
    {
        $cwd = getcwd() ?: '.';

        if (! File::exists("{$cwd}/vendor/bin/pest")) {
            info('pest not installed, installing it via composer...');
            exec('composer remove phpunit/phpunit');
            exec('composer require pestphp/pest --dev --with-all-dependencies');
        }

        if (
            ! File::exists("{$cwd}/vendor/pestphp/pest-plugin-type-coverage") &&
            confirm('Do you want to install the Pest type coverage plugin?', true)
        ) {
            exec('composer require pestphp/pest-plugin-type-coverage --dev');
        }
    }
}
