<?php

namespace Tracking3\Core\Client\Http;

use JsonException;
use JsonSerializable;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\Exception\EmptyOrMalformedRequestBody;
use Tracking3\Core\Client\Exception\Exception;
use Tracking3\Core\Client\Exception\Forbidden;
use Tracking3\Core\Client\Exception\MethodNotAllowed;
use Tracking3\Core\Client\Exception\NotFound;
use Tracking3\Core\Client\Exception\RequestTimeout;
use Tracking3\Core\Client\Exception\RuntimeException;
use Tracking3\Core\Client\Exception\ServerError;
use Tracking3\Core\Client\Exception\TooManyRequests;
use Tracking3\Core\Client\Exception\Unauthorized;
use Tracking3\Core\Client\Exception\UnprocessableEntity;

class Http
{

    public const RESPONSE_CODE_200 = 200;
    public const RESPONSE_CODE_201 = 201;
    public const RESPONSE_CODE_204 = 204;
    public const RESPONSE_CODE_400 = 400;
    public const RESPONSE_CODE_401 = 401;
    public const RESPONSE_CODE_403 = 403;
    public const RESPONSE_CODE_404 = 404;
    public const RESPONSE_CODE_405 = 405;
    public const RESPONSE_CODE_408 = 408;
    public const RESPONSE_CODE_422 = 422;
    public const RESPONSE_CODE_429 = 429;
    public const RESPONSE_CODE_500 = 500;


    /**
     * @var Configuration
     */
    protected $configuration;


    /**
     * @var resource|RequestHandlerInterface
     */
    protected $requestHandler;


    /**
     * Http constructor.
     *
     * @param Configuration $configuration
     * @param RequestHandlerInterface $requestHandler
     */
    public function __construct(
        Configuration $configuration,
        RequestHandlerInterface $requestHandler
    ) {
        $this->configuration = $configuration;
        $this->requestHandler = $requestHandler;
    }


    /**
     * @param null|int $statusCode
     * @param null|string $message
     * @throws Exception
     */
    public static function throwStatusCodeException(
        int $statusCode = null,
        string $message = null
    ): void {
        switch ($statusCode) {
            case self::RESPONSE_CODE_400:
                throw new EmptyOrMalformedRequestBody(
                    $message,
                    1592834790
                );
            case self::RESPONSE_CODE_401:
                throw new Unauthorized(
                    $message,
                    1592834791
                );
            case self::RESPONSE_CODE_403:
                throw new Forbidden(
                    $message,
                    1592834792
                );
            case self::RESPONSE_CODE_404:
                throw new NotFound(
                    $message,
                    1592834793
                );
            case self::RESPONSE_CODE_405:
                throw new MethodNotAllowed(
                    $message,
                    1592834794
                );
            case self::RESPONSE_CODE_408:
                throw new RequestTimeout(
                    $message,
                    1592834795
                );
            case self::RESPONSE_CODE_422:
                throw new UnprocessableEntity(
                    $message,
                    1592834796
                );
            case self::RESPONSE_CODE_429:
                throw new TooManyRequests(
                    $message,
                    1592834797
                );
            case self::RESPONSE_CODE_500:
                throw new ServerError(
                    $message,
                    1592834798
                );
            default:
                throw new RuntimeException(
                    'Unexpected HTTP response code #' . $statusCode,
                    1592834799
                );
        }
    }


    /**
     * @param $payload
     * @return array
     * @throws JsonException
     */
    private static function handlePayload($payload): array
    {
        if (is_object($payload)) {
            if (!$payload instanceof JsonSerializable) {
                throw new RuntimeException(
                    'If $payload is an object it MUST implement JsonSerializable interface.',
                    1593161057
                );
            }

            $payload = json_decode(
                json_encode(
                    $payload,
                    JSON_THROW_ON_ERROR
                ),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        }

        return $payload;
    }


    /**
     * @param string $uri
     * @param null|array|JsonSerializable $payload
     * @param null $file
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function create(
        string $uri,
        $payload = null,
        $file = null
    ): array {


        $response = $this->doRequest(
            'POST',
            $uri,
            self::handlePayload($payload),
            $file,
        );

        if ($response['status'] === self::RESPONSE_CODE_201) {
            return json_decode($response['body'], true, 512, JSON_THROW_ON_ERROR);
        }

        self::throwStatusCodeException($response['status']);
    }


    /**
     * @param string $uri
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function get(
        string $uri
    ): array {

        $response = $this->doRequest(
            'GET',
            $uri,
        );

        if ($response['status'] === self::RESPONSE_CODE_200) {
            return json_decode($response['body'], true, 512, JSON_THROW_ON_ERROR);
        }

        self::throwStatusCodeException($response['status']);
    }


    /**
     * @param string $uri
     * @param null|array|JsonSerializable $payload
     * @return array
     * @throws JsonException
     * @throws Exception
     */
    public function update(
        string $uri,
        $payload = null
    ): array {

        $response = $this->doRequest(
            'PUT',
            $uri,
            self::handlePayload($payload),
        );

        if ($response['status'] === self::RESPONSE_CODE_200) {
            return json_decode($response['body'], true, 512, JSON_THROW_ON_ERROR);
        }

        self::throwStatusCodeException($response['status']);
    }


    /**
     * @param $uri
     * @return bool
     * @throws Exception
     */
    public function delete(
        string $uri
    ): bool {

        $response = $this->doRequest(
            'DELETE',
            $uri
        );

        if ($response['status'] === self::RESPONSE_CODE_204) {
            return true;
        }

        self::throwStatusCodeException($response['status']);
    }


    /**
     * @param string $httpMethod
     * @param string $uri
     * @param null|array $requestBody
     * @param null $file
     * @param null|array $customHeaders
     * @return array
     */
    private function doRequest(
        string $httpMethod,
        string $uri,
        array $requestBody = null,
        $file = null,
        array $customHeaders = []
    ): array {
        return $this->requestHandler->doRequest(
            $httpMethod,
            $uri,
            $this->configuration,
            $requestBody,
            $file,
            $customHeaders
        );
    }
}
