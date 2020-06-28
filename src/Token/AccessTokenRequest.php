<?php

namespace Tracking3\Core\Client\Token;

use JsonException;
use Tracking3\Core\Client\AbstractRequest;
use Tracking3\Core\Client\EnvironmentHandlingService;

class AccessTokenRequest extends AbstractRequest
{
    /**
     * @return string
     * @throws JsonException
     */
    public function getAccessToken(): string
    {
        $uri = implode(
            '/',
            [
                EnvironmentHandlingService::buildBaseUri($this->configuration),
                'token',
                'access',
            ]
        );

        $response = $this->getHttp()->get($uri);

        $this->configuration->setAccessToken($response['payload']['jwt']);

        return $this->configuration->getAccessToken();
    }
}
