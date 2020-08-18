<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Fixtures;

use Tracking3\Core\Client\AbstractRequest;

class AbstractRequestImplementationFixture extends AbstractRequest
{
    public function getSomething()
    {
        $this->doAutoLogin();
    }
}
