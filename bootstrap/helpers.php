<?php

declare(strict_types=1);

if (! function_exists('absolute_path')) {
    function absolute_path(string $path = ''): string
    {
        return getcwd().DIRECTORY_SEPARATOR.$path;
    }
}
