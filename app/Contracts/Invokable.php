<?php

declare(strict_types=1);

namespace App\Contracts;

interface Invokable
{
    public function __invoke(): void;
}
