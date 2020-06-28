<?php

namespace Tracking3\Core\Client\Http;

interface HttpRequest
{
    /**
     * @param string $name
     * @param mixed $value
     */
    public function setOption(string $name, $value);


    /**
     * @return bool|string
     */
    public function execute();


    /**
     * @param string $name
     * @return mixed
     */
    public function getInfo(string $name);


    /**
     * @return int
     */
    public function getErrorCode(): int;


    /**
     * @return string
     */
    public function getError(): string;


    /**
     * @return void
     */
    public function close(): void;
}
