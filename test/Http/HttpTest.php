<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Http;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\Exception\EmptyOrMalformedRequestBody;
use Tracking3\Core\Client\Exception\FailedDependency;
use Tracking3\Core\Client\Exception\Forbidden;
use Tracking3\Core\Client\Exception\MethodNotAllowed;
use Tracking3\Core\Client\Exception\NotFound;
use Tracking3\Core\Client\Exception\RequestTimeout;
use Tracking3\Core\Client\Exception\RuntimeException;
use Tracking3\Core\Client\Exception\ServerError;
use Tracking3\Core\Client\Exception\TooManyRequests;
use Tracking3\Core\Client\Exception\Unauthorized;
use Tracking3\Core\Client\Exception\UnprocessableEntity;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Http\Http;
use Tracking3\Core\Client\Http\RequestHandlerInterface;
use Tracking3\Core\ClientTest\Fixtures\ApiObjectFixture;
use Tracking3\Core\ClientTest\ReflectionTrait;

class HttpTest extends TestCase
{

    use ReflectionTrait;

    public function testThrowStatusCodeException400(): void
    {
        $message = 'my-custom-message';

        $this->expectException(EmptyOrMalformedRequestBody::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834790);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_400, $message);

    }


    public function testThrowStatusCodeException401(): void
    {
        $message = 'my-custom-message';

        $this->expectException(Unauthorized::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834791);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_401, $message);
    }


    public function testThrowStatusCodeException403(): void
    {
        $message = 'my-custom-message';


        $this->expectException(Forbidden::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834792);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_403, $message);
    }


    public function testThrowStatusCodeException404(): void
    {
        $message = 'my-custom-message';

        $this->expectException(NotFound::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834793);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_404, $message);
    }


    public function testThrowStatusCodeException405(): void
    {
        $message = 'my-custom-message';

        $this->expectException(MethodNotAllowed::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834794);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_405, $message);

    }


    public function testThrowStatusCodeException408(): void
    {
        $message = 'my-custom-message';

        $this->expectException(RequestTimeout::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834795);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_408, $message);
    }


    public function testThrowStatusCodeException422(): void
    {
        $message = 'my-custom-message';

        $this->expectException(UnprocessableEntity::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834796);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_422, $message);
    }


    public function testThrowStatusCodeException424(): void
    {
        $message = 'my-custom-message';

        $this->expectException(FailedDependency::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834800);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_424, $message);
    }


    public function testThrowStatusCodeException429(): void
    {
        $message = 'my-custom-message';

        $this->expectException(TooManyRequests::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834797);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_429, $message);
    }


    public function testThrowStatusCodeException500(): void
    {
        $message = 'my-custom-message';

        $this->expectException(ServerError::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834798);
        Http::throwStatusCodeException(Http::RESPONSE_CODE_500, $message);
    }


    public function testThrowStatusCodeExceptionUnknown(): void
    {
        $message = 'Unexpected HTTP response code #1';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(1592834799);
        Http::throwStatusCodeException(1, $message);
    }


    public function testCreateWithArrayPayloadWillSucceed(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'POST',
            ['request' => 'payload'],
            [
                'status' => 201,
                'body' => '{"response": "payload"}',
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $response = $httpMock->create(
            'my/uri',
            ['request' => 'payload'],
        );

        self::assertEquals(['response' => 'payload'], $response);
    }


    public function testCreateWithObjectPayloadWillSucceed(): void
    {
        $payload = new ApiObjectFixture();
        $payload->foo = 'foo';
        $payload->bar = 'bar';

        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'POST',
            $payload->jsonSerialize(),
            [
                'status' => 201,
                'body' => '{"response": "payload"}',
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $response = $httpMock->create(
            'my/uri',
            $payload,
        );

        self::assertEquals(['response' => 'payload'], $response);
    }


    public function testCreateWithNonSerializableObjectPayloadWillFail(): void
    {
        $payload = new stdClass();
        $payload->foo = 'bar';
        $payload->bar = 'foo';

        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $this->expectException(RuntimeException::class);

        $httpMock->create(
            'my/uri',
            $payload,
        );
    }


    public function testCreateWillThrowServerException(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'POST',
            ['request' => 'payload'],
            [
                'status' => 500,
                'body' => '{"response": "payload"}',
            ],
        );

        $httpMock = $this->getHttpMock($curlMock);

        $this->expectException(ServerError::class);

        $httpMock->create(
            'my/uri',
            ['request' => 'payload'],
        );
    }


    public function testGetWithArrayPayloadWillSucceed(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'GET',
            null,
            [
                'status' => 200,
                'body' => '{"response": "payload"}',
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $response = $httpMock->get(
            'my/uri',
        );

        self::assertEquals(['response' => 'payload'], $response);
    }


    public function testGetWillThrowServerException(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'GET',
            null,
            [
                'status' => 500,
                'body' => '{"response": "payload"}',
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $this->expectException(ServerError::class);

        $httpMock->get(
            'my/uri',
        );
    }


    public function testUpdateWithArrayPayloadWillSucceed(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'PUT',
            ['request' => 'payload'],
            [
                'status' => 200,
                'body' => '{"response": "payload"}',
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $response = $httpMock->update(
            'my/uri',
            ['request' => 'payload'],
        );

        self::assertEquals(['response' => 'payload'], $response);
    }


    public function testUpdateWillThrowServerException(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'PUT',
            ['request' => 'payload'],
            [
                'status' => 500,
                'body' => '{"response": "payload"}',
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $this->expectException(ServerError::class);

        $httpMock->update(
            'my/uri',
            ['request' => 'payload'],
        );
    }


    public function testDeleteWithArrayPayloadWillSucceed(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'DELETE',
            null,
            [
                'status' => 204,
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $response = $httpMock->delete(
            'my/uri',
        );

        self::assertTrue($response);
    }


    public function testDeleteWillThrowServerException(): void
    {
        $curlMock = $this->getMockBuilder(CurlRequestHandler::class)
            ->getMock();

        $this->prepareHandlerMock(
            $curlMock,
            'DELETE',
            null,
            [
                'status' => 500,
            ],
        );

        /** @var Http|MockObject $httpMock */
        $httpMock = $this->getHttpMock($curlMock);

        $this->expectException(ServerError::class);

        $httpMock->delete(
            'my/uri',
        );
    }


    /**
     * @param RequestHandlerInterface|MockObject $requestHandlerMock
     * @return Http|MockObject
     */
    protected function getHttpMock($requestHandlerMock)
    {
        /** @var Http|MockObject $httpMock */
        return $this->getMockBuilder(Http::class)
            ->setConstructorArgs(
                [
                    $this->getConfiguration(),
                    $requestHandlerMock,
                ]
            )
            ->setMethodsExcept(
                [
                    'create',
                    'get',
                    'update',
                    'delete',
                ]
            )
            ->getMock();
    }


    /**
     * @return Configuration
     */
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
     * @param RequestHandlerInterface|MockObject $requestHandlerMock
     * @param string $httpMethod
     * @param $requestPayload
     * @param $willReturn
     */
    protected function prepareHandlerMock(
        $requestHandlerMock,
        string $httpMethod,
        $requestPayload,
        $willReturn
    ): void {
        $requestHandlerMock->expects(self::once())
            ->method('doRequest')
            ->with(
                $httpMethod,
                'my/uri',
                $this->getConfiguration(),
                $requestPayload,
                null,
                [],
            )->willReturn(
                $willReturn
            );
    }
}
