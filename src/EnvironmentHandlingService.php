<?php

declare(strict_types=1);

namespace Tracking3\Core\Client;

use JetBrains\PhpStorm\Pure;

class EnvironmentHandlingService
{
    public const API_URI_ENV_DEVELOPMENT = 'http://tr3-core';


    public const API_URI_ENV_PRODUCTION = 'https://api.tracking3.io';


    public const API_VERSION = 'v1';


    public const ENV_DEVELOPMENT = 'development';


    public const ENV_PRODUCTION = 'production';


    public const SELF_VERSION = 'v2.0.1';


    /**
     * @param Configuration $configuration
     * @return string
     */
    #[Pure] public static function buildBaseUri(
        Configuration $configuration
    ): string {

        return match ($configuration->getEnvironment()) {
            self::ENV_DEVELOPMENT => self::API_URI_ENV_DEVELOPMENT . '/' . $configuration->getApiVersion(),
            default => self::API_URI_ENV_PRODUCTION . '/' . $configuration->getApiVersion(),
        };
    }
}
