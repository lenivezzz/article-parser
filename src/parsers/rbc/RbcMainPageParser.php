<?php
declare(strict_types=1);

namespace php_part\parsers\rbc;

use php_part\parsers\AbstractPageParser;
use Symfony\Component\DomCrawler\Crawler;

class RbcMainPageParser extends AbstractPageParser implements RbcMainPageParserInterface
{
    /**
     * @return array
     */
    public function parseFeedUrlList() : array
    {
        return $this->findNode('.js-news-feed-list')
            ->filter('a.js-news-feed-item')
            ->each(static function (Crawler $node) {
                return $node->attr('href');
            });
    }
}
