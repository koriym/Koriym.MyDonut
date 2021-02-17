<?php

declare(strict_types=1);

namespace Koriym\MyDonut;

interface DonutInterface
{
    public function addHole(DonutInterface $donut): void;

    public function etag(): Etag;

    public function stale(): void;
}