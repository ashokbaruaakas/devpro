<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

test('pint-json.stub exists', function () {
    expect(File::exists(config('devpro.stubs_path').'/pint-json.stub'))->toBeTrue();
});

test('larastan-neon.stub exists', function () {
    expect(File::exists(config('devpro.stubs_path').'/larastan-neon.stub'))->toBeTrue();
});
