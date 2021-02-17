<?php

declare(strict_types=1);

namespace Koriym\MyDonut;

use DateTimeInterface;

interface MyDonutInterface
{
    public function isModified(string $etag): void;

    public function setClock(DateTimeInterface $dateTime): void;

    public function update(DonutInterface $donut, string $contents = ''): void;

    public function stale(DonutInterface $donut): void;
}
