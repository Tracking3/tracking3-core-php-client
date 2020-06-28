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
        $this->configuration = $configuration;
        $this->doAutoLogin();
    }


    /**
     * @return Organisation\OrganisationRequest
     */
    public function organisation()
    {
        return new Organisation\OrganisationRequest($this->configuration);
    }


    /**
     * @return Token\AccessTokenRequest
     */
    public function accessToken()
    {
        return new Token\AccessTokenRequest($this->configuration);
    }


    /**
     * @return Token\RefreshTokenRequest
     */
    public function refreshToken()
    {
        return new Token\RefreshTokenRequest($this->configuration);
    }


    protected function doAutoLogin()
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
