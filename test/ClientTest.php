<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest;

use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Client;

class ClientTest extends TestCase
{
    public function testClassInstances(): void
    {
        self::assertTrue(method_exists(Client::class, 'accessToken'));
        self::assertTrue(method_exists(Client::class, 'refreshToken'));
        self::assertTrue(method_exists(Client::class, 'organisation'));
    }
}
