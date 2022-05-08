<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Http;

interface HttpRequest
{
    /**
     * @param int $name
     * @param mixed $value
     */
    public function setOption(
        int $name,
        mixed $value
    );


    /**
     * @return bool|string
     */
    public function execute(): bool|string;


    /**
     * @param int $name
     * @return mixed
     */
    public function getInfo(int $name): mixed;


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
