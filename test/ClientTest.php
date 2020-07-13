<?php

namespace Tracking3\Core\ClientTest;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tracking3\Core\Client\Client;
use Tracking3\Core\Client\Configuration;

class ClientTest extends TestCase
{
    public function testClassInstances(): void
    {
        self::assertTrue(method_exists(Client::class, 'accessToken'));
        self::assertTrue(method_exists(Client::class, 'refreshToken'));
        self::assertTrue(method_exists(Client::class, 'organisation'));
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
