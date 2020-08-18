<?php

declare(strict_types=1);

namespace Tracking3\Core\Client;

use JsonException;
use Tracking3\Core\Client\Exception\InvalidArgumentException;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Http\Http;
use Tracking3\Core\Client\Http\RequestHandlerInterface;

abstract class AbstractRequest
{

    /**
     * @var Configuration
     */
    protected $configuration;


    /**
     * @var Http
     */
    protected $http;


    /**
     * @var RequestHandlerInterface
     */
    protected $requestHandler;


    /**
     * @var string
     */
    protected $uuidV4Regex = '/(?i)[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}/';


    /**
     * @param Configuration $configuration
     * @param RequestHandlerInterface|null $requestHandler
     */
    public function __construct(
        Configuration $configuration,
        RequestHandlerInterface $requestHandler = null
    ) {
        $this->requestHandler = $requestHandler ?? new CurlRequestHandler();
        $this->configuration = $configuration;
        $this->http = new Http(
            $configuration,
            $this->requestHandler,
        );
    }


    /**
     * @return Http
     */
    public function getHttp(): Http
    {
        return $this->http;
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
            $this->configuration->getClient()->refreshToken()->get(false);
            $this->configuration->getClient()->accessToken()->get();
        }
    }


    /**
     * @param string $uuidV4
     * @return bool
     */
    public function isUuidV4Valid(string $uuidV4): bool
    {
        if (preg_match($this->uuidV4Regex, $uuidV4)) {
            return true;
        }

        throw new InvalidArgumentException(
            sprintf(
                'The given string identifier \'%s\' does not match the expected pattern of a UUID version 4.',
                $uuidV4
            ),
            1594666596
        );
    }

}
