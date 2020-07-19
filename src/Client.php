<?php

namespace Tracking3\Core\Client;

class Client
{
    /**
     * @var Configuration
     */
    private $configuration;


    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $configuration->setClient($this);
        $this->configuration = $configuration;
    }


    /**
     * @return Organisation\OrganisationRequest
     */
    public function organisation(): Organisation\OrganisationRequest
    {
        return new Organisation\OrganisationRequest($this->configuration);
    }


    /**
     * @return Token\AccessTokenRequest
     */
    public function accessToken(): Token\AccessTokenRequest
    {
        return new Token\AccessTokenRequest($this->configuration);
    }


    /**
     * @return Token\RefreshTokenRequest
     */
    public function refreshToken(): Token\RefreshTokenRequest
    {
        return new Token\RefreshTokenRequest($this->configuration);
    }


    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }


    /**
     * @return bool
     */
    public function hasConfiguration(): bool
    {
        return null !== $this->configuration;
    }
}
