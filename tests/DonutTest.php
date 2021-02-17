<?php

declare(strict_types=1);

namespace Koriym\MyDonut;

use PHPUnit\Framework\TestCase;

class DonutTest extends TestCase
{
    /** @var Donut */
    protected $donut;

    protected function setUp(): void
    {
        $this->donut = new Donut('post', new Vary(['id' => 1]));
    }

    public function testIsInstanceOfMyDonut(): void
    {
        $actual = $this->donut;
        $this->assertInstanceOf(Donut::class, $actual);
    }

    public function testAddHole(): Donut
    {
        $donut = new Donut('post', new Vary(['id' => 1]));
        $donut->addHole(new Donut('comment', new Vary(['id' => 10])));
        $donut->addHole(new Donut('ad', new Vary([])));
        $numOfHole = count($donut);
        $this->assertSame(2, $numOfHole);

        return $donut;
    }

    /**
     * @depends testAddHole
     */
    public function testEtag(Donut $donut)
    {
        $etag = $donut->etag();
        $etagString = (string) $etag;
        $etagArray = $etag->getHoleEtags();
        $this->assertSame('name:post-vary:1-rev:0', $etagString);
    }
}

