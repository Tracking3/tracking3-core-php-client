<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Fixtures;

use JsonSerializable;

class ApiObjectFixture implements JsonSerializable
{
    /**
     * @var mixed
     */
    public $bar;


    /**
     * @var mixed
     */
    public $foo;


    public function jsonSerialize(): array
    {

        return [
            'bar' => $this->bar,
            'foo' => $this->foo,
        ];
    }
}
