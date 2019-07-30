<?php
declare(strict_types=1);

namespace php_part\exceptions;

use Throwable;

class NodeNotFoundException extends UnexpectedContentException
{
    /**
     * @param string $selector
     * @param string $content
     * @param Throwable|null $previous
     * @return NodeNotFoundException
     */
    public static function create(
        string $selector,
        string $content,
        Throwable $previous = null
    ) : NodeNotFoundException {
        return new self(
            sprintf('Block "%s" not found in %s %s', $selector, PHP_EOL, $content),
            0,
            $previous
        );
    }
}
