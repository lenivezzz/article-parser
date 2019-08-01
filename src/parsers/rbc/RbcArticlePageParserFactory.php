<?php
declare(strict_types=1);

namespace php_part\parsers\rbc;

use php_part\exceptions\ParserFactoryException;
use php_part\parsers\ArticlePageParserFactoryInterface;
use php_part\parsers\ArticlePageParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class RbcArticlePageParserFactory implements ArticlePageParserFactoryInterface
{
    private const RBC_ARTICLE_HOST = 'www.rbc.ru';

    /**
     * @inheritDoc
     */
    public function createByUrl(string $url) : ArticlePageParserInterface
    {
        $host = parse_url($url, PHP_URL_HOST);
        switch ($host) {
            case self::RBC_ARTICLE_HOST:
                return new RbcArticlePageParser(new Crawler());
            default:
                throw new ParserFactoryException(sprintf('Parser for articles %s is not implemented', $host));
        }
    }
}
