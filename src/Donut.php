<?php

namespace Koriym\MyDonut;

use Countable;
use Stringable;

class Donut implements DonutInterface, Countable
{
    private string $name;
    private Vary $vary;
    /** @var array<string, DonutInterface> */
    private array $holes = [];
    private ?Stringable $content;
    private int $rev = 0;

    public function __construct(string $name, Vary $vary, ?Stringable $content = null)
    {
        $this->name = $name;
        $this->vary = $vary;
        $this->content = $content;
    }

    public function addHole(DonutInterface $donut): void
    {
        $this->holes[] = $donut;
    }

    public function etag(?Etag $etag = null): Etag
    {
        $etag = $etag ?? new Etag($this->name, $this->vary);
        foreach ($this->holes as $hole)
        {
            $etag->add($hole->etag());
        }
        $etag->setRev($this->rev);

        return $etag;
    }


    public function stale(): void
    {
        $this->rev++;
    }

    /**
     * Count number of holes
     */
    public function count()
    {
        return count($this->holes);
    }
}
