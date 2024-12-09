<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Invokable;
use Illuminate\Support\Facades\File;

final class MakeHomeDir implements Invokable
{
    public function __invoke(): void
    {
        $userConfigPath = user_config_path();
        when(! File::exists($userConfigPath), fn () => File::makeDirectory($userConfigPath));
    }
}
