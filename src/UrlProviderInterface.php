<?php
declare(strict_types=1);

namespace php_part;

interface UrlProviderInterface
{
    /**
     * @return array
     */
    public function provideUrlList() : array;
}
