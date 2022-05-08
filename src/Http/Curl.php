<?php
// @codeCoverageIgnoreStart

declare(strict_types=1);

namespace Tracking3\Core\Client\Http;

use CurlHandle;
use Tracking3\Core\Client\Exception\RuntimeException;

class Curl implements HttpRequest
{
    private CurlHandle $curl;


    public function __construct()
    {

        $curl = curl_init();

        if (!$curl) {
            throw new RuntimeException(
                'Missing cURL extension',
                null,
                1651840652
            );
        }

        $this->curl = $curl;
    }


    /**
     * @inheritDoc
     */
    public function setOption(
        int $name,
        $value
    ): void {

        curl_setopt(
            $this->curl,
            $name,
            $value
        );
    }


    /**
     * @inheritDoc
     */
    public function execute(): bool|string
    {

        return curl_exec($this->curl);
    }


    /**
     * @inheritDoc
     */
    public function getInfo(int $name): mixed
    {

        return curl_getinfo(
            $this->curl,
            $name
        );
    }


    /**
     * @inheritDoc
     */
    public function getErrorCode(): int
    {

        return curl_errno($this->curl);
    }


    /**
     * @inheritDoc
     */
    public function getError(): string
    {

        return curl_error($this->curl);
    }


    /**
     * @inheritDoc
     */
    public function close(): void
    {

        curl_close($this->curl);
    }
}
