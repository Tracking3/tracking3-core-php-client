<?php

namespace Tracking3\Core\Client;

use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Http\Http;
use Tracking3\Core\Client\Http\RequestHandlerInterface;

abstract class AbstractRequest
{

    /**
     * @var Configuration
     */
    protected $configuration;


    /**
     * @var Http
     */
    protected $http;


    /**
     * @var RequestHandlerInterface
     */
    protected $requestHandler;


    /**
     * @param Configuration $configuration
     * @param RequestHandlerInterface|null $requestHandler
     */
    public function __construct(
        Configuration $configuration,
        RequestHandlerInterface $requestHandler = null
    ) {
        $this->requestHandler = $requestHandler ?? new CurlRequestHandler();
        $this->configuration = $configuration;
        $this->http = new Http(
            $configuration,
            $this->requestHandler,
        );
    }


    /**
     * @return Http
     */
    public function getHttp(): Http
    {
        return $this->http;
    }
}
