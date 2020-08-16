<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Token;

use JsonException;
use Tracking3\Core\Client\AbstractRequest;
use Tracking3\Core\Client\EnvironmentHandlingService;

class RefreshTokenRequest extends AbstractRequest
{
    /**
     * @param bool $rememberMe
     * @return string
     * @throws JsonException
     */
    public function get(
        bool $rememberMe = false
    ): string {
        $uri = implode(
            '/',
            [
                EnvironmentHandlingService::buildBaseUri($this->configuration),
                'token',
                'refresh',
            ]
        );

        $uri .= '?';
        $uri .= 'remember-me='
            . ($rememberMe
                ? 'true'
                : 'false');

        $response = $this->getHttp()->get($uri);

        $this->configuration->setRefreshToken($response['payload']['jwt']);

        return $this->configuration->getRefreshToken();
    }
}
