<?php

declare(strict_types=1);

namespace Tracking3\Core\Client;

use JsonException;
use JsonSerializable;
use Tracking3\Core\Client\Exception\EmptyOrMalformedRequestBody;
use Tracking3\Core\Client\Exception\Exception;
use Tracking3\Core\Client\Exception\FailedDependency;
use Tracking3\Core\Client\Exception\Forbidden;
use Tracking3\Core\Client\Exception\InvalidArgumentException;
use Tracking3\Core\Client\Exception\MethodNotAllowed;
use Tracking3\Core\Client\Exception\NotFound;
use Tracking3\Core\Client\Exception\RequestTimeout;
use Tracking3\Core\Client\Exception\RuntimeException;
use Tracking3\Core\Client\Exception\ServerError;
use Tracking3\Core\Client\Exception\TooManyRequests;
use Tracking3\Core\Client\Exception\Unauthorized;
use Tracking3\Core\Client\Exception\UnprocessableEntity;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Http\RequestHandlerInterface;

abstract class AbstractRequest
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
    public const RESPONSE_CODE_424 = 424;
    public const RESPONSE_CODE_429 = 429;
    public const RESPONSE_CODE_500 = 500;


    protected string $uuidV4Regex = '/(?i)[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}/';


    public function __construct(
        protected Configuration $configuration,
        protected RequestHandlerInterface $requestHandler = new CurlRequestHandler()
    ) {
    }


    /**
     * @throws JsonException
     */
    protected function doAutoLogin(): void
    {

        if (
            null === $this->configuration->getAccessToken()
            && $this->configuration->isDoAutoLogin()
        ) {
            $this->configuration->getClient()
                ->refreshToken()
                ->get(false);
            $this->configuration->getClient()
                ->accessToken()
                ->get();
        }
    }


    public static function handleNonSuccessResponse(
        array $response
    ): void {

        throw match ((int)($response['statusCode'] ?? null)) {
            self::RESPONSE_CODE_400 => new EmptyOrMalformedRequestBody(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800400
            ),
            self::RESPONSE_CODE_401 => new Unauthorized(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800401
            ),
            self::RESPONSE_CODE_403 => new Forbidden(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800403
            ),
            self::RESPONSE_CODE_404 => new NotFound(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800404
            ),
            self::RESPONSE_CODE_405 => new MethodNotAllowed(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800405
            ),
            self::RESPONSE_CODE_408 => new RequestTimeout(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800408
            ),
            self::RESPONSE_CODE_422 => new UnprocessableEntity(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800422
            ),
            self::RESPONSE_CODE_424 => new FailedDependency(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800424
            ),
            self::RESPONSE_CODE_429 => new TooManyRequests(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800429
            ),
            self::RESPONSE_CODE_500 => new ServerError(
                $response['statusPhrase'] ?? null,
                $response['messages'] ?? null,
                1592800500
            ),
            default => new RuntimeException(
                sprintf(
                    'Unexpected HTTP response code #%s',
                    $response['statusCode'] ?? ''
                ),
                null,
                1592800001
            ),
        };
    }


    /**
     * @param $payload
     * @return array|null
     * @throws JsonException
     */
    private static function handlePayload($payload): ?array
    {

        if (is_object($payload)) {
            if (!$payload instanceof JsonSerializable) {
                throw new RuntimeException(
                    'If $payload is an object it MUST implement JsonSerializable interface.',
                    null,
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
     * @param string $uuidV4
     * @return bool
     */
    public function isUuidV4Valid(string $uuidV4): bool
    {

        if (preg_match(
            $this->uuidV4Regex,
            $uuidV4
        )) {
            return true;
        }

        throw new InvalidArgumentException(
            sprintf(
                'The given string identifier \'%s\' does not match the expected pattern of a UUID version 4.',
                $uuidV4
            ),
            null,
            1594666596
        );
    }


    /**
     * @param string $uri
     * @param null|array|JsonSerializable $payload
     * @param null|resource $file
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function createReal(
        string $uri,
        array|JsonSerializable $payload = null,
        $file = null
    ): array {


        $response = $this->doRequest(
            'POST',
            $uri,
            self::handlePayload($payload),
            $file,
        );

        $response['body'] = json_decode(
            $response['body'],
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if ($response['status'] === self::RESPONSE_CODE_201) {
            return $response['body'];
        }

        self::handleNonSuccessResponse($response['body']);
    }


    /**
     * @param string $uri
     * @return array
     * @throws Exception
     * @throws JsonException
     */
    public function getReal(
        string $uri
    ): array {

        $response = $this->doRequest(
            'GET',
            $uri,
        );

        $response['body'] = json_decode(
            $response['body'],
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if ($response['status'] === self::RESPONSE_CODE_200) {
            return $response['body'];
        }

        self::handleNonSuccessResponse($response['body']);
    }


    /**
     * @param string $uri
     * @param null|array|JsonSerializable $payload
     * @return array
     * @throws JsonException
     * @throws Exception
     */
    public function updateReal(
        string $uri,
        array|JsonSerializable $payload = null
    ): array {

        $response = $this->doRequest(
            'PATCH',
            $uri,
            self::handlePayload($payload),
        );


        $response['body'] = json_decode(
            $response['body'],
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if ($response['status'] === self::RESPONSE_CODE_200) {
            return $response['body'];
        }

        self::handleNonSuccessResponse($response['body']);
    }


    /**
     * @param string $uri
     * @param null|array|JsonSerializable $payload
     * @return array
     * @throws JsonException
     * @throws Exception
     */
    public function replaceReal(
        string $uri,
        array|JsonSerializable $payload = null
    ): array {

        $response = $this->doRequest(
            'PUT',
            $uri,
            self::handlePayload($payload),
        );

        $response['body'] = json_decode(
            $response['body'],
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if ($response['status'] === self::RESPONSE_CODE_200) {
            return $response['body'];
        }

        self::handleNonSuccessResponse($response['body']);
    }


    /**
     * @param string $uri
     * @return bool
     * @throws JsonException
     */
    public function deleteReal(
        string $uri
    ): bool {

        $response = $this->doRequest(
            'DELETE',
            $uri
        );

        $response['body'] = json_decode(
            $response['body'],
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if ($response['status'] === self::RESPONSE_CODE_204) {
            return true;
        }

        self::handleNonSuccessResponse($response['body']);
    }


    /**
     * @param string $httpMethod
     * @param string $uri
     * @param null|array $requestBody
     * @param null|resource $file
     * @param array $customHeaders
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
