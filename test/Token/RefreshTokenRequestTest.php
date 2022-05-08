<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Token;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Token\RefreshTokenRequest;
use Tracking3\Core\ClientTest\ConfigurationTrait;
use Tracking3\Core\ClientTest\Http\CurlMock;

class RefreshTokenRequestTest extends TestCase
{
    use ConfigurationTrait;

    /**
     * @throws JsonException
     * @dataProvider dataProviderForRefreshToken
     */
    public function testGetRefreshToken(
        null|bool $rememberMe,
        string $expectedRememberMe
    ): void {

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

        $request = new RefreshTokenRequest(
            $this->getConfiguration(),
            new CurlRequestHandler($curlMock)
        );


        // test result
        if (null === $rememberMe) {
            self::assertEquals(
                'json.web.token',
                $request->get()
            );
        } else {
            self::assertEquals(
                'json.web.token',
                $request->get($rememberMe)
            );
        }


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
                    'refresh?remember-me=' . $expectedRememberMe,
                ]
            ),
            $curlMock->getOption(CURLOPT_URL)
        );
    }


    public function dataProviderForRefreshToken(): array
    {

        return [
            'absent' => [
                'remember-me' => null,
                'expected-remember-me' => 'false',
            ],
            'false' => [
                'remember-me' => false,
                'expected-remember-me' => 'false',
            ],
            'true' => [
                'remember-me' => true,
                'expected-remember-me' => 'true',
            ],
        ];
    }

}
