<?php

declare(strict_types=1);

namespace Koriym\MyDonut;

use DateTimeInterface;

final class MyDonut implements MyDonutInterface
{
    public function isModified(string $etag): void
    {
        // TODO: Implement isModified() method.
    }

    public function setClock(DateTimeInterface $dateTime): void
    {
        // TODO: Implement setClock() method.
    }

    public function update(DonutInterface $donut, string $contents = ''): void
    {
        // TODO: Implement update() method.
    }

    public function stale(DonutInterface $donut): void
    {
        // TODO: Implement stale() method.
    }

}
