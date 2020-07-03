<?php

namespace Tracking3\Core\ClientTest\Http;

use Tracking3\Core\Client\Http\HttpRequest;

class CurlMock implements HttpRequest
{

    /**
     * @var array
     */
    public $options = [];


    /**
     * @var int
     */
    public $executed = 0;


    /**
     * @var array
     */
    public $info = [
        CURLINFO_HTTP_CODE => 200,
    ];


    /**
     * 0 (zero) -> no error
     *
     * @var int
     */
    public $errorCode = 0;


    /**
     * @var string
     */
    public $error = '';


    /**
     * @var int
     */
    public $closed = 0;


    /**
     * @var mixed
     */
    public $result;


    public function setOption(string $name, $value)
    {
        $this->options[$name] = $value;
    }


    public function execute()
    {
        $this->executed++;
        return $this->result;
    }


    public function getInfo(string $name)
    {
        return $this->info[$name];
    }


    public function getErrorCode(): int
    {
        return $this->errorCode;
    }


    public function getError(): string
    {
        return $this->error;
    }


    public function close(): void
    {
        $this->closed++;
    }
}
