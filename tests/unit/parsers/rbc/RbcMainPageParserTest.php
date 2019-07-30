<?php
declare(strict_types=1);

namespace tests\unit\parsers\rbc;

use InvalidArgumentException;
use php_part\exceptions\NodeNotFoundException;
use php_part\parsers\rbc\RbcMainPageParser;
use php_part\parsers\rbc\RbcMainPageParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class RbcMainPageParserTest extends AbstractHtmlPageParserTest
{
    public function testParseUrlList() : void
    {
        $pageParser = $this->createUrlParser($this->loadHtmlFileContent('main_page.html'));
        $this->assertEquals(
            [
                'https://www.rbc.ru/crypto/news/5d3ff4fa9a7947f6101f0ffb?from=newsfeed',
                'https://www.rbc.ru/rbcfreenews/5d3ff76d9a7947f7acac65c8?from=newsfeed',
                'https://quote.rbc.ru/news/forecast/5d3ed6c39a7947851546b93b?from=newsfeed',
            ],
            $pageParser->parseFeedUrlList()
        );

        $pageParser = $this->createUrlParser('<div class="js-news-feed-list"></div>');
        $this->assertEquals([], $pageParser->parseFeedUrlList());

        $this->expectException(NodeNotFoundException::class);
        $this->createUrlParser('<div></div>')->parseFeedUrlList();
    }

    public function testSetInvalidContent() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUrlParser('')->parseFeedUrlList();
    }

    /**
     * @param string $content
     * @return RbcMainPageParserInterface
     */
    private function createUrlParser(string $content) : RbcMainPageParserInterface
    {
        $parser = new RbcMainPageParser(new Crawler());
        $parser->setContent($content);
        return $parser;
    }
}
