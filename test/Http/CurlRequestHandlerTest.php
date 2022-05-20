<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Http;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Exception\Connection;
use Tracking3\Core\Client\Exception\Timeout;
use Tracking3\Core\Client\Http\CurlRequestHandler;

class CurlRequestHandlerTest extends TestCase
{

    /**
     * @throws JsonException
     */
    public function testDoRequestDefault(): void
    {

        $returnPayload = [
            'foo' => 'bar',
            'miep',
        ];

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler($returnPayload);

        $return = $requestHandler->doRequest(
            'GET',
            'my/uri',
            $this->getConfiguration(),
        );

        // options
        self::assertEquals(
            'GET',
            $curlMock->options[CURLOPT_CUSTOMREQUEST]
        );
        self::assertEquals(
            'my/uri',
            $curlMock->options[CURLOPT_URL]
        );
        self::assertEquals(
            60,
            $curlMock->options[CURLOPT_TIMEOUT]
        );

        // headers
        $this->assertHeaders(
            $curlMock,
            [
                'Accept: application/json',
                'Authorization: Basic am9obkBleGFtcGxlLmNvbTpzM2NyMzc=',
                'Content-Type: application/json',
                'User-Agent: Tracking3 Core PHP Client ' . EnvironmentHandlingService::SELF_VERSION,
                'X-Strip-Leading-Brackets: false',
            ]
        );

        // request was send
        self::assertEquals(
            1,
            $curlMock->executed
        );

        // connection was closed
        self::assertEquals(
            1,
            $curlMock->closed
        );

        // return payload
        self::assertSame(
            [
                'status' => $curlMock->info[CURLINFO_HTTP_CODE],
                'body' => json_encode(
                    $returnPayload,
                    JSON_THROW_ON_ERROR
                ),
            ],
            $return
        );
    }


    /**
     * @throws JsonException
     */
    public function testDoRequestExpectTimeout(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
        ] = $this->prepareRequestHandler(
            null,
            28,
            0,
        );
        $configuration = clone $this->getConfiguration();
        $configuration->setTimeout(1);

        $this->expectException(Timeout::class);
        $this->expectExceptionMessage(
            sprintf(
                'Request exceeded timeout of %d',
                $configuration->getTimeout()
            )
        );
        $this->expectExceptionCode(1592833821);

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration,
        );
    }


    /**
     * @throws JsonException
     */
    public function testDoRequestExpectConnectionError(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler(
            null,
            23,
            0,
        );
        $configuration = clone $this->getConfiguration();
        $curlMock->error = 'my-connection-error';

        $this->expectException(Connection::class);
        $this->expectExceptionMessage('my-connection-error');
        $this->expectExceptionCode(23);

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration,
        );
    }


    /**
     * @throws JsonException
     */
    public function testDoRequestCustomHeaders(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler();

        $configuration = clone $this->getConfiguration();
        $configuration->setIdApplication('my-custom-app');

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration,
            null,
            null,
            [
                'Accept' => 'foo/bar',
                'Authorization' => 'something else',
                'Content-Type' => 'foo/bar',
                'User-Agent' => 'something else',
                'X-Id-Application' => 'my-custom-app',
                'X-Strip-Leading-Brackets' => 'miep',
                'X-something-else' => 'something else',
                'something-else' => 'something else',
            ]
        );

        $this->assertHeaders(
            $curlMock,
            [
                'Accept: foo/bar',
                'Authorization: something else',
                'Content-Type: foo/bar',
                'User-Agent: something else',
                'X-Id-Application: my-custom-app',
                'X-Strip-Leading-Brackets: miep',
                'X-something-else: something else',
                'something-else: something else',
            ]
        );
    }


    /**
     * @throws JsonException
     */
    public function testAuthorizationHeaderEmailPassword(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler();

        $configuration = clone $this->getConfiguration();
        $configuration->setEmail('foo@bar.com');
        $configuration->setPassword('miep');

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration
        );

        // headers
        $this->assertHeaders(
            $curlMock,
            [
                'Accept: application/json',
                'Authorization: Basic Zm9vQGJhci5jb206bWllcA==',
                'Content-Type: application/json',
                'User-Agent: Tracking3 Core PHP Client ' . EnvironmentHandlingService::SELF_VERSION,
                'X-Strip-Leading-Brackets: false',
            ]
        );
    }


    /**
     * @throws JsonException
     */
    public function testAuthorizationHeaderRefreshToken(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler();

        $configuration = clone $this->getConfiguration();
        $configuration->setRefreshToken('json.refresh.token');

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration
        );

        // headers
        $this->assertHeaders(
            $curlMock,
            [
                'Accept: application/json',
                'Authorization: Bearer json.refresh.token',
                'Content-Type: application/json',
                'User-Agent: Tracking3 Core PHP Client ' . EnvironmentHandlingService::SELF_VERSION,
                'X-Strip-Leading-Brackets: false',
            ]
        );
    }


    /**
     * @throws JsonException
     */
    public function testAuthorizationHeaderAccessToken(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler();

        $configuration = clone $this->getConfiguration();
        $configuration->setAccessToken('json.access.token');

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration
        );

        // headers
        $this->assertHeaders(
            $curlMock,
            [
                'Accept: application/json',
                'Authorization: Bearer json.access.token',
                'Content-Type: application/json',
                'User-Agent: Tracking3 Core PHP Client ' . EnvironmentHandlingService::SELF_VERSION,
                'X-Strip-Leading-Brackets: false',
            ]
        );
    }


    public function testIdApiTransactionHeaderIsPresent(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler();

        $configuration = clone $this->getConfiguration();
        $configuration->setIdApiTransaction('uuid-api-transaction');

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration
        );

        // headers
        $this->assertHeaders(
            $curlMock,
            [
                'Accept: application/json',
                'Authorization: Basic am9obkBleGFtcGxlLmNvbTpzM2NyMzc=',
                'Content-Type: application/json',
                'User-Agent: Tracking3 Core PHP Client ' . EnvironmentHandlingService::SELF_VERSION,
                'X-Strip-Leading-Brackets: false',
                'X-Id-Api-Transaction: uuid-api-transaction',
            ]
        );
    }


    /**
     * @throws JsonException
     */
    public function testRequestBodyFormatJsonPayload(): void
    {

        /** @var CurlRequestHandler $requestHandler */
        /** @var CurlMock $curlMock */
        [
            $requestHandler,
            $curlMock,
        ] = $this->prepareRequestHandler();


        $configuration = clone $this->getConfiguration();
        $configuration->setIdApiTransaction('uuid-api-transaction');

        $requestBody = [
            'foo' => 'bar',
            'baz' => [
                'bazfoo' => 'bar',
                'bazbar' => 'foo',
                'numbers' => [
                    'braz',
                    'miep',
                ],
            ],
        ];

        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $configuration,
            $requestBody,
        );

        // request payload
        self::assertEquals(
            $requestBody,
            json_decode(
                $curlMock->getOption(CURLOPT_POSTFIELDS),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }


    protected function getConfiguration(): Configuration
    {

        return new Configuration(
            [
                'email' => 'john@example.com',
                'password' => 's3cr37',
            ]
        );
    }


    /**
     * @param CurlMock $curlMock
     * @param array $expectedHeaders
     */
    protected function assertHeaders(
        CurlMock $curlMock,
        array $expectedHeaders
    ): void
    {

        $additionalHeaders = array_diff(
            $curlMock->options[CURLOPT_HTTPHEADER],
            $expectedHeaders
        );
        self::assertCount(
            0,
            $additionalHeaders,
            sprintf(
                'Found unexpected additional or wrong headers: %s',
                implode(
                    ', ',
                    $additionalHeaders
                )
            )
        );

        $missingHeaders = array_diff(
            $expectedHeaders,
            $curlMock->options[CURLOPT_HTTPHEADER]
        );
        self::assertCount(
            0,
            $missingHeaders,
            sprintf(
                'Missing headers: %s',
                implode(
                    ', ',
                    $missingHeaders
                )
            )
        );
    }


    /**
     * @param array $returnPayload
     * @param int $errorCode
     * @param int $httpStatusCode
     * @return array
     * @throws JsonException
     */
    protected function prepareRequestHandler(
        ?array $returnPayload = null,
        int $errorCode = 0,
        int $httpStatusCode = 200
    ): array
    {

        $curlMock = new CurlMock();
        $curlMock->result = ")]}',\n" . json_encode(
                $returnPayload,
                JSON_THROW_ON_ERROR
            );
        $curlMock->errorCode = $errorCode;
        $curlMock->info[CURLINFO_HTTP_CODE] = $httpStatusCode;

        $requestHandler = new CurlRequestHandler(
            $curlMock
        );

        return [
            $requestHandler,
            $curlMock,
        ];
    }
}
