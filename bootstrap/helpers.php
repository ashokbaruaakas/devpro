<?php

declare(strict_types=1);

if (! function_exists('user_config_path')) {
    function user_config_path(string $path = ''): string
    {
        return $_SERVER['HOME'].DIRECTORY_SEPARATOR.'.config'.DIRECTORY_SEPARATOR.'prodev'.DIRECTORY_SEPARATOR.$path;
    }
}
