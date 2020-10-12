<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Http;

use Tracking3\Core\Client\Configuration;

interface RequestHandlerInterface
{

    /**
     * @param string $httpMethod
     * @param string $uri
     * @param Configuration $configuration
     * @param null|array $requestBody
     * @param null|resource $file
     * @param null|array $customHeaders ['Header-Name' => 'header value']
     * @return array
     */
    public function doRequest(
        string $httpMethod,
        string $uri,
        Configuration $configuration,
        array $requestBody = null,
        $file = null,
        array $customHeaders = []
    ): array;
}
