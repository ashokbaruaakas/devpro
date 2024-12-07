<?php

declare(strict_types=1);

return [
    'stubs_path' => base_path('stubs'),

    'scripts' => [
        'dev' => 'npx concurrently -c "#c4b5fd,#fb7185,#fdba74" "php artisan queue:listen --tries=1" "php artisan pail --timeout=0" "npm run dev" --names=QUEUE,LOGS,VITE',
        'build' => 'npm run build',
        'test' => 'php artisan test',
        'format' => './vendor/bin/pint',
        'analyse' => './vendor/bin/phpstan analyse',
    ],
];
