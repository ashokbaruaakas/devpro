<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

test('configure command exists', function () {
    expect(File::exists(base_path('app/Commands/Configure.php')))->toBeTrue();
});
