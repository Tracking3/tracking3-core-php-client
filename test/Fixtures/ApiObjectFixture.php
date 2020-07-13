<?php

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


    public function jsonSerialize()
    {
        return [
            'bar' => $this->bar,
            'foo' => $this->foo,
        ];
    }
}
