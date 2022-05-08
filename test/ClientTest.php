<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest;

use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Client;

class ClientTest extends TestCase
{
    use ConfigurationTrait;

    /**
     * @param string $method
     * @return void
     * @dataProvider dataProviderForTestClassInstances
     */
    public function testClassInstances(
        string $method
    ): void {

        self::assertTrue(
            method_exists(
                Client::class,
                $method
            )
        );
    }


    public function testCanCreateClient(): void
    {

        $client = new Client(
            $this->getConfiguration()
        );

        self::assertInstanceOf(
            Client::class,
            $client
        );
    }


    public function dataProviderForTestClassInstances(): array
    {

        return [
            'accessToken' => [
                'method' => 'accessToken',
            ],
            'refreshToken' => [
                'method' => 'refreshToken',
            ],
            'organisation' => [
                'method' => 'organisation',
            ],
        ];
    }

}
