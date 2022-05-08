<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest;

use Tracking3\Core\Client\Configuration;

trait ConfigurationTrait
{
    public function getConfiguration(): Configuration
    {

        return new Configuration(
            [
                'email' => 'john@example.com',
                'password' => 's3cr37',
                'doAutoLogin' => false,
            ]
        );
    }
}
