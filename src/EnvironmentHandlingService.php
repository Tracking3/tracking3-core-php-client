<?php

declare(strict_types=1);

namespace Tracking3\Core\Client;

class EnvironmentHandlingService
{
    public const API_URI_ENV_DEVELOPMENT = 'http://api.tracking3.local/api';


    public const API_URI_ENV_PRODUCTION = 'https://api.tracking3.io/api';


    public const API_VERSION = 'v1.0.0';


    public const ENV_DEVELOPMENT = 'development';


    public const ENV_PRODUCTION = 'production';


    public const SELF_VERSION = '0.0.1-rc1';


    /**
     * @param Configuration $configuration
     * @return string
     */
    public static function buildBaseUri(
        Configuration $configuration
    ): ?string {
        switch ($configuration->getEnvironment()) {
            case self::ENV_DEVELOPMENT:
                return self::API_URI_ENV_DEVELOPMENT . '/' . $configuration->getApiVersion();
            case self::ENV_PRODUCTION:
            default:
                return self::API_URI_ENV_PRODUCTION . '/' . $configuration->getApiVersion();
        }
    }
}
