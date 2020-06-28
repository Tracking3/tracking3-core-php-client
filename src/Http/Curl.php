<?php
// @codeCoverageIgnoreStart

namespace Tracking3\Core\Client\Http;

class Curl implements HttpRequest
{

    /**
     * @var false|resource
     */
    private $curl;


    /**
     * CurlRequestHandler constructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();
    }


    /**
     * @inheritDoc
     */
    public function setOption(
        string $name,
        $value
    ): void {
        curl_setopt($this->curl, $name, $value);
    }


    /**
     * @inheritDoc
     */
    public function execute()
    {
        return curl_exec($this->curl);
    }


    /**
     * @inheritDoc
     */
    public function getInfo(string $name)
    {
        return curl_getinfo($this->curl, $name);
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
