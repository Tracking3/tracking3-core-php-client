<?php

declare(strict_types=1);

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
    public function get(): string
    {
        $uri = implode(
            '/',
            [
                EnvironmentHandlingService::buildBaseUri($this->configuration),
                'token',
                'access',
            ]
        );

        $response = $this->getReal($uri);

        $this->configuration->setAccessToken($response['payload']['jwt']);

        return $this->configuration->getAccessToken();
    }
}
