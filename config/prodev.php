<?php

declare(strict_types=1);

return [
    'stubs_path' => base_path('stubs'),

    'scripts' => [
        'logs-clear' => 'find storage/logs -type f ! -name ".gitignore" -delete',

        'build' => 'npm run build',
        'dev' => 'npx concurrently -c "#c4b5fd,#fb7185,#fdba74" "php artisan queue:listen --tries=1 --timeout=0" "php artisan pail --timeout=0" "npm run dev" --names=QUEUE,LOGS,VITE',

        'pint' => './vendor/bin/pint',

        'lint' => [
            '@pint',
            'npm run lint',
        ],
        'test:lint' => [
            './vendor/bin/pint --test',
        ],
        'test:types' => [
            './vendor/bin/phpstan analyse --memory-limit=1G',
            // 'npm run test:types',
        ],
        'test:type-coverage' => './vendor/bin/pest --type-coverage --min=100 --memory-limit=1G',
        'test:unit' => './vendor/bin/pest --parallel --ci --coverage --min=100.0',

        'test' => [
            '@test:lint',
            '@test:types',
            '@test:type-coverage',
            '@test:unit',
        ],
    ],
];
