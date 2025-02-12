<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (! function_exists('absolute_path')) {
    function absolute_path(string $path = ''): string
    {
        if (filled($path) && ! Str::startsWith(DIRECTORY_SEPARATOR, $path)) {
            $path = DIRECTORY_SEPARATOR.$path;
        }

        return getcwd().$path;
    }
}
