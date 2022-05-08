<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Exception;

use RuntimeException;

class Exception extends RuntimeException
{
    protected ?array $messages;


    /**
     * @param null|string $statusPhrase
     * @param null|array $messages
     * @param null|int $code
     */
    public function __construct(
        ?string $statusPhrase = null,
        ?array $messages = null,
        ?int $code = 0
    ) {

        $statusPhrase = $statusPhrase ?? 'Internal Server Error';

        $this->messages = $messages;
        parent::__construct(
            $statusPhrase,
            $code
        );
    }


    /**
     * @return bool
     */
    public function hasMessages(): bool
    {

        return null !== $this->messages;
    }


    /**
     * @return null|array
     */
    public function getMessages(): ?array
    {

        return $this->messages;
    }

}
