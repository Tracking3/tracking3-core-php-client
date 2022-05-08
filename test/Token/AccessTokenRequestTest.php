<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Token;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Http\Http;
use Tracking3\Core\Client\Token\AccessTokenRequest;
use Tracking3\Core\ClientTest\ConfigurationTrait;
use Tracking3\Core\ClientTest\Http\CurlMock;

class AccessTokenRequestTest extends TestCase
{
    use ConfigurationTrait;

    /**
     * @throws JsonException
     */
    public function testGetAccessToken(): void
    {

        $curlMock = new CurlMock();
        $curlMock->info = [
            CURLINFO_HTTP_CODE => 200,
        ];

        $curlMock->result = ")]}',\n" . json_encode(
                [
                    'payload' => [
                        // payload to object mapping is tested somewhere else
                        'jwt' => 'json.web.token',
                    ],
                ],
                JSON_THROW_ON_ERROR
            );

        $request = new AccessTokenRequest(
            $this->getConfiguration(),
            new CurlRequestHandler($curlMock)
        );


        // test result
        self::assertEquals(
            'json.web.token',
            $request->get()
        );


        // test executed
        self::assertEquals(
            1,
            $curlMock->executed
        );


        // test url
        self::assertEquals(
            implode(
                '/',
                [
                    EnvironmentHandlingService::API_URI_ENV_PRODUCTION,
                    EnvironmentHandlingService::API_VERSION,
                    'token',
                    'access',
                ]
            ),
            $curlMock->getOption(CURLOPT_URL)
        );
    }
}
