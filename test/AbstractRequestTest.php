<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest;

use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Client;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\ClientTest\Fixtures\ConcreteRequest;

class AbstractRequestTest extends TestCase
{
    public function testAutoLogin(): void
    {
        $configuration = clone $this->getConfiguration();

        $clientMock = $this->getMockBuilder(Client::class)
            ->setConstructorArgs(
                [
                    $configuration,
                ]
            )
            ->getMock();

        $clientMock->expects(self::once())
            ->method('refreshToken');
        $clientMock->expects(self::once())
            ->method('accessToken');

        $request = new ConcreteRequest($configuration);
        $request->getSomething();
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
