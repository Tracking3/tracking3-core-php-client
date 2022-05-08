<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Http;

use Tracking3\Core\Client\Http\HttpRequest;

class CurlMock implements HttpRequest
{

    public int $closed = 0;


    public string $error = '';


    /**
     * 0 (zero) -> no error
     */
    public int $errorCode = 0;


    public int $executed = 0;


    public array $info = [
        CURLINFO_HTTP_CODE => 200,
    ];


    public array $options = [];


    public mixed $result = ")]}',\n{}";


    public function setOption(
        int $name,
        $value
    ): void {

        $this->options[$name] = $value;
    }


    public function getOption(
        int $name
    ): mixed {

        return $this->options[$name];
    }


    public function execute(): bool|string
    {

        $this->executed++;
        return $this->result;
    }


    public function getInfo(int $name): mixed
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
