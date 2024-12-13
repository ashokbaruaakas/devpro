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
        $cwd = getcwd() ?: '.';

        if (! File::exists("{$cwd}/vendor/laravel/pail")) {
            info('Pail not installed, installing it via composer...');
            exec('composer require --dev laravel/pail');
        }
    }
}
