<?php

declare(strict_types=1);

namespace Tracking3\Core\Client;

use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Http\RequestHandlerInterface;

class Client
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly RequestHandlerInterface $requestHandler = new CurlRequestHandler()
    ) {

        $this->configuration->setClient($this);
    }


    public function organisation(): Organisation\OrganisationRequest
    {

        return new Organisation\OrganisationRequest(
            $this->configuration,
            $this->requestHandler,
        );
    }


    public function accessToken(): Token\AccessTokenRequest
    {

        return new Token\AccessTokenRequest(
            $this->configuration,
            $this->requestHandler,
        );
    }


    public function refreshToken(): Token\RefreshTokenRequest
    {

        return new Token\RefreshTokenRequest(
            $this->configuration,
            $this->requestHandler,
        );
    }


    public function getConfiguration(): Configuration
    {

        return $this->configuration;
    }
}
