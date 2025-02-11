<?php

declare(strict_types=1);

namespace App\Support;

use App\Actions\WriteConfiguration;
use App\Constants\LiteralValue;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\info;

/**
 * @phpstan-type ScriptShape array{name: string, command: string}
 * @phpstan-type ConfigurationShape array{scripts: ScriptShape[]}
 */
final class Configuration
{
    private const JSON_CONFIGURATION_NAME = 'devpulse.json';

    public function __construct(
        /** @var ScriptShape[] */
        private array $scripts = LiteralValue::EMPTY_ARRAY
    ) {
        // ...
    }

    public static function filePath(): string
    {
        return absolute_path(self::JSON_CONFIGURATION_NAME);
    }

    public static function exists(): bool
    {
        return File::exists(self::filePath());
    }

    public static function delete(): bool
    {
        return File::delete(self::filePath());
    }

    public static function make(): self
    {
        if (! self::exists()) {
            info('Look like devpulse is not configured yet. Configuring now...');

            resolve(WriteConfiguration::class)->handle();
        }

        $jsonContents = File::get(self::filePath());
        $jsonContentsAsArray = json_decode($jsonContents, true);

        return new self($jsonContentsAsArray['scripts']);
    }

    public function scripts(string ...$keys): array
    {
        if (blank($keys)) {
            return $this->scripts;
        }

        return Arr::where($this->scripts, fn ($script) => in_array($script['name'], $keys));
    }
}
