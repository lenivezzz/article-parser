<?php
declare(strict_types=1);

namespace php_part\resources\rbc;

use php_part\exceptions\ParserFactoryException;
use php_part\resources\ArticlePageParserFactoryInterface;
use php_part\resources\ArticlePageParserInterface;
use php_part\resources\rbc\parsers\RbcArticlePageParser;
use php_part\resources\rbc\parsers\RbcStyleArticlePageParser;
use Symfony\Component\DomCrawler\Crawler;

class RbcArticlePageParserFactory implements ArticlePageParserFactoryInterface
{
    private const RBC_ARTICLE_HOST = 'www.rbc.ru';
    private const RBC_QUOTE_ARTICLE_HOST = 'quote.rbc.ru';
    private const RBC_SPORT_ARTICLE_HOST = 'sport.rbc.ru';
    private const RBC_STYLE_ARTICLE_HOST = 'style.rbc.ru';

    /**
     * @inheritDoc
     */
    public function createByUrl(string $url) : ArticlePageParserInterface
    {
        $host = parse_url($url, PHP_URL_HOST);
        switch ($host) {
            case self::RBC_ARTICLE_HOST:
            case self::RBC_QUOTE_ARTICLE_HOST:
            case self::RBC_SPORT_ARTICLE_HOST:
                return new RbcArticlePageParser(new Crawler());
            case self::RBC_STYLE_ARTICLE_HOST:
                return new RbcStyleArticlePageParser(new Crawler());
            default:
                throw new ParserFactoryException(sprintf('Parser for articles %s is not implemented', $host));
        }
    }
}
