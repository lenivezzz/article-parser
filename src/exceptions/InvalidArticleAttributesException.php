<?php
declare(strict_types=1);

namespace php_part\exceptions;

use RuntimeException;
use Throwable;

class InvalidArticleAttributesException extends RuntimeException
{
    /**
     * @param array $messageList
     * @param Throwable|null $previous
     * @return InvalidArticleAttributesException
     */
    public static function create(array $messageList, Throwable $previous = null) : InvalidArticleAttributesException
    {
        return new static(implode(PHP_EOL, $messageList), 0, $previous);
    }
}
