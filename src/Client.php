<?php

namespace Tracking3\Core\Client;

use JsonException;

class Client
{
    /**
     * @var Configuration
     */
    private $configuration;


    /**
     * @param Configuration $configuration
     * @throws JsonException
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->doAutoLogin();
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
     * @throws JsonException
     */
    protected function doAutoLogin(): void
    {
        if (
            !$this->configuration->hasAccessToken()
            && $this->configuration->isDoAutoLogin()
        ) {
            $this->refreshToken()->getRefreshToken(false);
            $this->accessToken()->getAccessToken();
        }
    }
}
