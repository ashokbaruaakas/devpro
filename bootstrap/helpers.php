<?php

declare(strict_types=1);

if (! function_exists('user_config_path')) {
    function user_config_path(string $path = ''): string
    {
        return $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.devpro'.DIRECTORY_SEPARATOR.$path;
    }
}
