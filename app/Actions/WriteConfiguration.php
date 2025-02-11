<?php

declare(strict_types=1);

namespace App\Actions;

use App\Constants\LiteralValue;
use App\Support\Configuration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

final class WriteConfiguration
{
    public function handle(?Collection $configurationContent = LiteralValue::NULL): void
    {
        if (blank($configurationContent)) {
            $configurationContent = collect([
                'scripts' => [
                    // TODO:: Add Some Default Scripts
                ],
            ]);
        }

        File::put(
            Configuration::filePath(),
            $configurationContent->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}
