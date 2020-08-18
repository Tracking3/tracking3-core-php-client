<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\AbstractRequest;
use Tracking3\Core\Client\Client;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\Exception\InvalidArgumentException;
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


    protected function getConfiguration(): Configuration
    {
        return new Configuration(
            [
                'email' => 'john@example.com',
                'password' => 's3cr37',
            ]
        );
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
     * @return MockObject|AbstractRequest
     */
    protected function getRequestMock()
    {
        /** @var AbstractRequest $mock */
        return $this->getMockBuilder(AbstractRequestImplementationFixture::class)
            ->setMethodsExcept(['isUuidV4Valid'])
            ->disableOriginalConstructor()
            ->getMock();

    }
}
