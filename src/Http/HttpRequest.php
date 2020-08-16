<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Http;

interface HttpRequest
{
    /**
     * @param int $name
     * @param mixed $value
     */
    public function setOption(int $name, $value);


    /**
     * @return bool|string
     */
    public function execute();


    /**
     * @param int $name
     * @return mixed
     */
    public function getInfo(int $name);


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
