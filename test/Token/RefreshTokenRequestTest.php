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
    public function testGetRefreshTokenFallbackToRememberFalse(): void
    {

        $httpMock = $this->getHttpMock();
        $requestMock = $this->getRequestMock();

        // test $rememberMe = null -> false
        $httpMock->expects(self::once())
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

        $requestMock->method('getHttp')
            ->willReturn($httpMock);

        self::assertEquals('json.web.token', $requestMock->get());
    }


    /**
     * @throws JsonException
     */
    public function testGetRefreshTokenSetRememberFalse(): void
    {
        $httpMock = $this->getHttpMock();
        $requestMock = $this->getRequestMock();

        // test $rememberMe = false -> false
        $httpMock->expects(self::once())
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

        $requestMock->method('getHttp')
            ->willReturn($httpMock);

        self::assertEquals('json.web.token', $requestMock->get(false));
    }


    /**
     * @throws JsonException
     */
    public function testGetRefreshTokenSetRememberTrue(): void
    {
        $httpMock = $this->getHttpMock();
        $requestMock = $this->getRequestMock();

        // test $rememberMe = true -> true
        $httpMock->expects(self::once())
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

        self::assertEquals('json.web.token', $requestMock->get(true));
    }


    /**
     * @return Http|MockObject
     */
    protected function getHttpMock()
    {
        return $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();
    }


    /**
     * @return RefreshTokenRequest|MockObject
     */
    protected function getRequestMock()
    {
        $configuration = new Configuration(
            [
                'email' => 'john@example.com',
                'password' => 's3cr37',
            ]
        );

        return $this->getMockBuilder(RefreshTokenRequest::class)
            ->setConstructorArgs(
                [
                    $configuration,
                ]
            )
            ->setMethodsExcept(['get'])
            ->getMock();
    }
}
