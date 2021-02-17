<?php

declare(strict_types=1);

namespace Koriym\MyDonut;

interface EtagInterface
{
    public function isModified(string $etag): bool;
}
