<?php

declare(strict_types=1);

use App\Support\Configuration;

it('should be a singleton', function () {
    $configurationA = Configuration::instance();
    $configurationB = Configuration::instance();

    expect($configurationA)->toBe($configurationB);
});

test('fileName() should return the configuration file name', function () {
    expect(Configuration::fileName())->toBeString();
});

test('filePath() should return the configuration file path', function () {
    expect(Configuration::filePath())->toBeString()
        ->toBe(absolute_path(Configuration::fileName()));
});
