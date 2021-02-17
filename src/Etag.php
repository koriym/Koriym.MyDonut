<?php

namespace Koriym\MyDonut;

final class Etag
{
    /** @var list<Etag> */
    private array $etags = [];
    private int $rev;
    private string $name;
    private Vary $vary;

    public function __construct(string $name, Vary $vary)
    {
        $this->name = $name;
        $this->vary = $vary;
    }

    public function add(Etag $etag)
    {
        $this->etags[] = $etag;
    }

    public function setRev(int $rev)
    {
        $this->rev = $rev;
    }

    /**
     * @return list<sting>
     */
    public function getHoleEtags(): array
    {
        $array = [];
        foreach ($this->etags as $etag) {
            array_push($array, (string) $etag);
        }

        return $array;
    }

    public function __toString(): string
    {
        return sprintf('name:%s-vary:%s-rev:%s', $this->name, (string) $this->vary, $this->rev);
    }
}