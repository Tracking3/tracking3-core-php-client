<?php

declare(strict_types=1);

namespace Tracking3\Core\Client;

use InvalidArgumentException;

class Configuration
{
    public const ENVIRONMENTS = [
        EnvironmentHandlingService::ENV_PRODUCTION,
        EnvironmentHandlingService::ENV_DEVELOPMENT,
    ];


    private string $password;


    private string $email;


    private null|string $accessToken = null;


    private null|string $refreshToken = null;


    private string $environment = EnvironmentHandlingService::ENV_PRODUCTION;


    private string $apiVersion = EnvironmentHandlingService::API_VERSION;


    private int $timeout = 60;


    private null|string $idApplication;


    private null|string $idApiTransaction;


    /**
     * If `true` this client automatically tries to attempt an access token
     * before the first request is executed.
     * If `false` you have to deal with access tokens/login yourself.
     */
    private bool $doAutoLogin = true;


    private Client $client;


    /**
     * Configuration constructor.
     *
     * @param array $config
     * @example
     * $config = new Configuration([
     *     'password' => 's3cr37',
     *     'email' => 'john@example.com',
     *     'idApplication' => 'my-3rd-party-app-integration',
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
        $this->idApplication = $config['idApplication'] ?? null;
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
     * @param string $email
     * @return Configuration
     */
    public function setEmail(string $email): Configuration
    {
        $this->email = $email;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getAccessToken(): ?string
    {

        return $this->accessToken;
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
     * @return null|string
     */
    public function getRefreshToken(): ?string
    {

        return $this->refreshToken;
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
     * @param string $apiVersion
     * @return Configuration
     */
    public function setApiVersion(string $apiVersion): Configuration
    {
        $this->apiVersion = $apiVersion;
        return $this;
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
    public function getIdApplication(): ?string
    {
        return $this->idApplication;
    }


    /**
     * @param string $idApplication
     * @return Configuration
     */
    public function setIdApplication(string $idApplication): Configuration
    {
        $this->idApplication = $idApplication;
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
     * @param null|string $idApiTransaction
     */
    public function setIdApiTransaction(?string $idApiTransaction): void
    {
        $this->idApiTransaction = $idApiTransaction;
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
     * @param Client $client
     * @return Configuration
     */
    public function setClient(Client $client): Configuration
    {
        $this->client = $client;
        return $this;
    }
}
