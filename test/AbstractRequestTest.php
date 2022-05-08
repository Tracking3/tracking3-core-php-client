<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest;

use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\AbstractRequest;
use Tracking3\Core\Client\Client;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\Exception\EmptyOrMalformedRequestBody;
use Tracking3\Core\Client\Exception\FailedDependency;
use Tracking3\Core\Client\Exception\Forbidden;
use Tracking3\Core\Client\Exception\InvalidArgumentException;
use Tracking3\Core\Client\Exception\MethodNotAllowed;
use Tracking3\Core\Client\Exception\NotFound;
use Tracking3\Core\Client\Exception\RequestTimeout;
use Tracking3\Core\Client\Exception\RuntimeException;
use Tracking3\Core\Client\Exception\ServerError;
use Tracking3\Core\Client\Exception\TooManyRequests;
use Tracking3\Core\Client\Exception\Unauthorized;
use Tracking3\Core\Client\Exception\UnprocessableEntity;
use Tracking3\Core\ClientTest\Fixtures\AbstractRequestImplementationFixture;

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

        $request = new AbstractRequestImplementationFixture($configuration);
        $request->getSomething();
    }


    public function testIsUuidV4ValidForValidUuidV4(): void
    {

        $mock = $this->getRequestMock();

        self::assertTrue($mock->isUuidV4Valid('fa438bfe-cb93-47cb-be73-f4b87eb8c16b'));
        self::assertTrue($mock->isUuidV4Valid('bcb3d37d-8dab-443a-abe9-cec35128cf00'));
        self::assertTrue($mock->isUuidV4Valid('6bc79327-c8cb-493d-b065-1e96e195c701'));
        self::assertTrue($mock->isUuidV4Valid('579c9d31-cc9c-49f7-8bfd-cc66dde3a614'));
    }


    public function testIsUuidV4ValidForInvalidUuid(): void
    {

        $mock = $this->getRequestMock();

        $this->expectException(InvalidArgumentException::class);
        $mock->isUuidV4Valid('invalid');
    }


    public function testIsUuidV4ValidForValidUuidV1(): void
    {

        $mock = $this->getRequestMock();

        $this->expectException(InvalidArgumentException::class);
        $mock->isUuidV4Valid('83779ab4-c5c2-11ea-87d0-0242ac130003'); // uuid v1
    }


    public function testIsUuidV4ValidForValidUuidV6(): void
    {

        $mock = $this->getRequestMock();

        $this->expectException(InvalidArgumentException::class);
        $mock->isUuidV4Valid('1e1c57df-f6f8-6cb0-9b21-0800200c9a66'); // uuid v6
    }


    /**
     * @dataProvider dataProviderForTestThrowStatusCodeException
     * @param string $expectedException
     * @param int $expectedExceptionCode
     * @param string $expectedExceptionMessage
     * @param array $input
     * @return void
     */
    public function testThrowStatusCodeException(
        string $expectedException,
        int $expectedExceptionCode,
        string $expectedExceptionMessage,
        array $input
    ): void {

        $this->expectException($expectedException);
        $this->expectExceptionCode($expectedExceptionCode);
        $this->expectExceptionMessage($expectedExceptionMessage);

        AbstractRequest::handleNonSuccessResponse($input);
    }


    public function dataProviderForTestThrowStatusCodeException(): array
    {

        return [
            'error-400' => [
                'expectedException' => EmptyOrMalformedRequestBody::class,
                'expectedExceptionCode' => 1592800400,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_400,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-401' => [
                'expectedException' => Unauthorized::class,
                'expectedExceptionCode' => 1592800401,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_401,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-403' => [
                'expectedException' => Forbidden::class,
                'expectedExceptionCode' => 1592800403,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_403,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-404' => [
                'expectedException' => NotFound::class,
                'expectedExceptionCode' => 1592800404,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_404,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-405' => [
                'expectedException' => MethodNotAllowed::class,
                'expectedExceptionCode' => 1592800405,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_405,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-408' => [
                'expectedException' => RequestTimeout::class,
                'expectedExceptionCode' => 1592800408,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_408,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-422' => [
                'expectedException' => UnprocessableEntity::class,
                'expectedExceptionCode' => 1592800422,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_422,
                    'statusPhrase' => 'my-custom-message',
                    'messages' => [
                        'elementOrFieldset' => [
                            'validator' => 'message',
                        ],
                    ],
                ],
            ],
            'error-424' => [
                'expectedException' => FailedDependency::class,
                'expectedExceptionCode' => 1592800424,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_424,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-429' => [
                'expectedException' => TooManyRequests::class,
                'expectedExceptionCode' => 1592800429,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_429,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-500' => [
                'expectedException' => ServerError::class,
                'expectedExceptionCode' => 1592800500,
                'expectedExceptionMessage' => 'my-custom-message',
                'input' => [
                    'statusCode' => AbstractRequest::RESPONSE_CODE_500,
                    'statusPhrase' => 'my-custom-message',
                ],
            ],
            'error-unknown' => [
                'expectedException' => RuntimeException::class,
                'expectedExceptionCode' => 1592800001,
                'expectedExceptionMessage' => 'Unexpected HTTP response code #1',
                'input' => [
                    'statusCode' => 1,
                    'statusPhrase' => 'Unexpected HTTP response code #1',
                ],
            ],
        ];
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


    /**
     * @return AbstractRequest
     */
    protected function getRequestMock(): AbstractRequest
    {

        return new AbstractRequestImplementationFixture(
            $this->createStub(Configuration::class)
        );
    }
}
