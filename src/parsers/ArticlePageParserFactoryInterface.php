<?php
declare(strict_types=1);

namespace php_part\parsers;

interface ArticlePageParserFactoryInterface
{
    /**
     * @param string $url
     * @return ArticlePageParserInterface
     */
    public function createByUrl(string $url) : ArticlePageParserInterface;
}
