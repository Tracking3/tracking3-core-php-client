<?php

namespace Tracking3\Core\ClientTest;

use Tracking3\Core\Client\Configuration;

trait ConfigurationTrait
{
    public function getConfiguration(): Configuration
    {
        $configuration = new Configuration(
            [
                'email' => 'john@example.com',
                'password' => 's3cr37',
                'doAutoLogin' => false,
            ]
        );

//        $clientMock = $this->getMockBuilder(Client::class)
//            ->setConstructorArgs(
//                [
//                    $configuration,
//                ]
//            )
//            ->getMock();
//
//        $clientMock->expects(self::once())
//            ->method('refreshToken');
//        $clientMock->expects(self::once())
//            ->method('accessToken');
//
//        $configuration->setClient($clientMock);

        return $configuration;
    }
}
