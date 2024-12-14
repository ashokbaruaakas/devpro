<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Invokable;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

final class ConfigurePrettier implements Invokable
{
    public function __invoke(): void
    {
        $cwd = getcwd() ?: '.';

        if (! File::exists("{$cwd}/node_modules/prettier")) {
            info('prettier not installed, installing it via npm...');
            exec('npm install --save-dev --save-exact prettier');
        }

        if (! File::exists("{$cwd}/node_modules/prettier-plugin-organize-imports")) {
            info('prettier-plugin-organize-imports not installed, installing it via npm...');
            exec('npm install --save-dev prettier-plugin-organize-imports');
        }

        if (! File::exists("{$cwd}/node_modules/prettier-plugin-tailwindcss")) {
            info('prettier-plugin-tailwindcss not installed, installing it via npm...');
            exec('npm install --save-dev prettier-plugin-tailwindcss');
        }

        $targetPath = "{$cwd}/.prettierrc";

        if (File::exists($targetPath) &&
            ! confirm('Do you want to overwrite the existing prettier configuration file?', false)) {
            info('Prettier configuration skipped.');

            return;
        }

        File::copy($this->prettierStubPath(), $targetPath);
    }

    private function prettierStubPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('devpro.stubs_path'),
            'dot-prettierrc.stub',
        ]);
    }
}
