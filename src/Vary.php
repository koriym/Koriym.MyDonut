<?php

namespace Koriym\MyDonut;

use function implode;

class Vary
{
    /** @param array<string, mixed> $ids */
    private $ids;

    /** @param array<string, mixed> $ids */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function __toString(): string
    {
        return implode(',', $this->ids);
    }
}
