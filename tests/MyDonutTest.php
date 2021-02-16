<?php

declare(strict_types=1);

namespace Koriym\MyDonut;

use PHPUnit\Framework\TestCase;

class MyDonutTest extends TestCase
{
    /** @var MyDonut */
    protected $myDonut;

    protected function setUp(): void
    {
        $this->myDonut = new MyDonut();
    }

    public function testIsInstanceOfMyDonut(): void
    {
        $actual = $this->myDonut;
        $this->assertInstanceOf(MyDonut::class, $actual);
    }
}
