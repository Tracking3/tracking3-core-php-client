<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Token;

use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Http\Http;
use Tracking3\Core\Client\Token\RefreshTokenRequest;
use Tracking3\Core\ClientTest\ReflectionTrait;

class RefreshTokenRequestTest extends TestCase
{

    use ReflectionTrait;

    /**
     * @throws JsonException
     */
    public function testGetRefreshToken(): void
    {
        $configuration = new Configuration(
            [
                'email' => 'john@example.com',
                'password' => 's3cr37',
            ]
        );

        /** @var RefreshTokenRequest|MockObject $requestMock */
        $requestMock = $this->getMockBuilder(RefreshTokenRequest::class)
            ->setConstructorArgs(
                [
                    $configuration,
                ]
            )
            ->setMethodsExcept(['get'])
            ->getMock();

        $httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        // test $rememberMe = null -> false
        $httpMock->expects(self::at(0))
            ->method('get')
            ->with(
                implode(
                    '/',
                    [
                        EnvironmentHandlingService::API_URI_ENV_PRODUCTION,
                        EnvironmentHandlingService::API_VERSION,
                        'token',
                        'refresh?remember-me=false',
                    ]
                )
            )
            ->willReturn(['payload' => ['jwt' => 'json.web.token']]);

        // test $rememberMe = false -> false
        $httpMock->expects(self::at(1))
            ->method('get')
            ->with(
                implode(
                    '/',
                    [
                        EnvironmentHandlingService::API_URI_ENV_PRODUCTION,
                        EnvironmentHandlingService::API_VERSION,
                        'token',
                        'refresh?remember-me=false',
                    ]
                )
            )
            ->willReturn(['payload' => ['jwt' => 'json.web.token']]);

        // test $rememberMe = true -> true
        $httpMock->expects(self::at(2))
            ->method('get')
            ->with(
                implode(
                    '/',
                    [
                        EnvironmentHandlingService::API_URI_ENV_PRODUCTION,
                        EnvironmentHandlingService::API_VERSION,
                        'token',
                        'refresh?remember-me=true',
                    ]
                )
            )
            ->willReturn(['payload' => ['jwt' => 'json.web.token']]);

        $requestMock->method('getHttp')
            ->willReturn($httpMock);

        self::assertEquals('json.web.token', $requestMock->get());
        self::assertEquals('json.web.token', $requestMock->get(false));
        self::assertEquals('json.web.token', $requestMock->get(true));
    }
}
