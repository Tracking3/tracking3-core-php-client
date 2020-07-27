<?php

namespace Tracking3\Core\Client;

use InvalidArgumentException;

class Configuration
{
    public const ENVIRONMENTS = [
        EnvironmentHandlingService::ENV_PRODUCTION,
        EnvironmentHandlingService::ENV_DEVELOPMENT,
    ];


    /**
     * @var string
     */
    private $password;


    /**
     * @var string
     */
    private $email;


    /**
     * @var string
     */
    private $accessToken;


    /**
     * @var string
     */
    private $refreshToken;


    /**
     * @var string
     */
    private $environment = EnvironmentHandlingService::ENV_PRODUCTION;


    /**
     * @var string
     */
    private $apiVersion = EnvironmentHandlingService::API_VERSION;


    /**
     * @var int
     */
    private $timeout = 60;


    /**
     * @var null|string
     */
    private $applicationId;


    /**
     * @var null|string
     */
    private $idApiTransaction;


    /**
     * If `true` this client automatically tries to attempt an access token
     * before the first request is executed.
     * If `false` you have to deal with access tokens/login yourself.
     *
     * @var bool
     */
    private $doAutoLogin = true;


    /**
     * @var Client
     */
    private $client;


    /**
     * Configuration constructor.
     *
     * @param array $config
     * @example
     * $config = new Configuration([
     *     'password' => 's3cr37',
     *     'email' => 'john@example.com',
     *     'applicationId' => 'my-3rd-party-app-integration',
     *     'doAutoLogin' => true|false,
     *     'timeout' => 60,
     *     'apiVersion' => EnvironmentHandlingService::API_VERSION,
     *     'environment' => EnvironmentHandlingService::ENV_PRODUCTION,
     *     'idApiTransaction' => 'uuid-api-transaction',
     * ]);
     *
     */
    public function __construct(array $config = [])
    {
        if (isset($config['password'])) {
            $this->password = $config['password'];
        } else {
            throw new InvalidArgumentException(
                'No password provided, but is required',
                1592824383
            );
        }

        if (isset($config['email'])) {
            $this->email = $config['email'];
        } else {
            throw new InvalidArgumentException(
                'No email provided, but is required',
                1592824491
            );
        }

        $this->apiVersion = $config['apiVersion'] ?? $this->apiVersion;
        $this->applicationId = $config['applicationId'] ?? null;
        $this->doAutoLogin = $config['doAutoLogin'] ?? $this->doAutoLogin;
        $this->environment = $this->parseEnvironment($config['environment'] ?? null);
        $this->timeout = $config['timeout'] ?? $this->timeout;
        $this->idApiTransaction = $config['idApiTransaction'] ?? null;
    }


    /**
     * @param null|string $environment
     * @return string
     */
    public function parseEnvironment(?string $environment): string
    {
        if (in_array($environment, self::ENVIRONMENTS, true)) {
            return $environment;
        }

        return $this->environment;
    }


    /**
     * @return bool
     */
    public function hasPassword(): bool
    {
        return null !== $this->password;
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * @param string $password
     * @return Configuration
     */
    public function setPassword(string $password): Configuration
    {
        $this->password = $password;
        return $this;
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * @return bool
     */
    public function hasEmail(): bool
    {
        return null !== $this->email;
    }


    /**
     * @param string $email
     * @return Configuration
     */
    public function setEmail(string $email): Configuration
    {
        $this->email = $email;
        return $this;
    }


    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }


    /**
     * @return bool
     */
    public function hasAccessToken(): bool
    {
        return null !== $this->accessToken;
    }


    /**
     * @param string $accessToken
     * @return Configuration
     */
    public function setAccessToken(string $accessToken): Configuration
    {
        $this->accessToken = $accessToken;
        return $this;
    }


    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }


    /**
     * @return bool
     */
    public function hasRefreshToken(): bool
    {
        return null !== $this->refreshToken;
    }


    /**
     * @param string $refreshToken
     * @return Configuration
     */
    public function setRefreshToken(string $refreshToken): Configuration
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasEnvironment(): bool
    {
        return null !== $this->environment;
    }


    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }


    /**
     * @param string $environment
     * @return Configuration
     */
    public function setEnvironment(string $environment): Configuration
    {
        $this->environment = $environment;
        return $this;
    }


    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }


    /**
     * @return bool
     */
    public function hasApiVersion(): bool
    {
        return null !== $this->apiVersion;
    }


    /**
     * @param string $apiVersion
     * @return Configuration
     */
    public function setApiVersion(string $apiVersion): Configuration
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasTimeout(): bool
    {
        return null !== $this->timeout;
    }


    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }


    /**
     * @param int $timeout
     * @return Configuration
     */
    public function setTimeout(int $timeout): Configuration
    {
        $this->timeout = $timeout;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getApplicationId(): ?string
    {
        return $this->applicationId;
    }


    /**
     * @return bool
     */
    public function hasApplicationId(): bool
    {
        return null !== $this->applicationId;
    }


    /**
     * @param string $applicationId
     * @return Configuration
     */
    public function setApplicationId(string $applicationId): Configuration
    {
        $this->applicationId = $applicationId;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getIdApiTransaction(): ?string
    {
        return $this->idApiTransaction;
    }


    /**
     * @return bool
     */
    public function hasIdApiTransaction(): bool
    {
        return null !== $this->idApiTransaction;
    }


    /**
     * @param null|string $idApiTransaction
     */
    public function setIdApiTransaction(?string $idApiTransaction): void
    {
        $this->idApiTransaction = $idApiTransaction;
    }


    /**
     * @return bool
     */
    public function hasDoAutoLogin(): bool
    {
        return null !== $this->doAutoLogin;
    }


    /**
     * @return bool
     */
    public function isDoAutoLogin(): bool
    {
        return $this->doAutoLogin;
    }


    /**
     * @param bool $doAutoLogin
     * @return Configuration
     */
    public function setDoAutoLogin(bool $doAutoLogin): Configuration
    {
        $this->doAutoLogin = $doAutoLogin;
        return $this;
    }


    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }


    /**
     * @return bool
     */
    public function hasClient(): bool
    {
        return null !== $this->client;
    }


    /**
     * @param Client $client
     * @return Configuration
     */
    public function setClient(Client $client): Configuration
    {
        $this->client = $client;
        return $this;
    }
}
