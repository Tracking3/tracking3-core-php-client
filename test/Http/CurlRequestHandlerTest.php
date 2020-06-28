<?php

namespace Tracking3\Core\ClientTest\Http;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Http\HttpRequest;
use Tracking3\Core\ClientTest\ReflectionTrait;

class CurlRequestHandlerTest extends TestCase
{
    use ReflectionTrait;

    public function testDoRequest(): void
    {
        /** @var CurlRequestHandler|MockObject $requestHandler */
        $requestHandler = $this->getMockBuilder(CurlRequestHandler::class)
            ->setMethodsExcept(
                [
                    'doRequest',
                ]
            )
            ->getMock();
        $curlMock = $this->getMockBuilder(HttpRequest::class)
            ->getMock();


        $this->reflectProperties($requestHandler, ['curl' => $curlMock]);


        $requestHandler->doRequest(
            'GET',
            'my/uri',
            $this->getConfiguration(),
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
