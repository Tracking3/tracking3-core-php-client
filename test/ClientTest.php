<?php

namespace Tracking3\Core\ClientTest;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tracking3\Core\Client\Client;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\Organisation\OrganisationRequest;
use Tracking3\Core\Client\Token\AccessTokenRequest;
use Tracking3\Core\Client\Token\RefreshTokenRequest;

class ClientTest extends TestCase
{
    public function testClassInstances(): void
    {
        $configuration = clone $this->getConfiguration();
        $configuration->setDoAutoLogin(false);

        $client = new Client($configuration);
        $this->assertInstanceOf(AccessTokenRequest::class, $client->accessToken());
        $this->assertInstanceOf(RefreshTokenRequest::class, $client->refreshToken());
        $this->assertInstanceOf(OrganisationRequest::class, $client->organisation());
    }


    public function testAutoLogin(): void
    {
        $configuration = clone $this->getConfiguration();

        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->expects(self::once())
            ->method('refreshToken');
        $clientMock->expects(self::once())
            ->method('accessToken');

        $reflectedClass = new ReflectionClass(Client::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke(
            $clientMock,
            $configuration,
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

}
