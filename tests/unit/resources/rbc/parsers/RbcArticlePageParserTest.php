<?php
declare(strict_types=1);

namespace tests\unit\resources\rbc\parsers;

use InvalidArgumentException;
use php_part\exceptions\NodeNotFoundException;
use php_part\exceptions\UnexpectedContentException;
use php_part\resources\ArticlePageParserInterface;
use php_part\resources\rbc\parsers\RbcArticlePageParser;
use Symfony\Component\DomCrawler\Crawler;

class RbcArticlePageParserTest extends AbstractHtmlPageParserTest
{
    public function testParseTitle() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('article_page.html'));
        $this->assertEquals('Facebook не станет запускать', $articleParser->parseTitle());

        $this->expectException(NodeNotFoundException::class);
        $this->createPageParser('<div></div>')->parseTitle();
    }

    public function testParseText() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('article_page.html'));
        $this->assertEquals('<p>Криптовалютный проект Libra может быть закрыт</p>', $articleParser->parseText());

        $this->expectException(NodeNotFoundException::class);
        $this->createPageParser('<div></div>')->parseText();
    }

    public function testParsePublishDateTime() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('article_page.html'));
        $this->assertEquals('2019-07-30 11:01:05', $articleParser->parsePublishedDateTime()->format('Y-m-d H:i:s'));

        $this->expectException(NodeNotFoundException::class);
        $this->createPageParser('<div></div>')->parsePublishedDateTime();
    }

    public function testParseWrongPublishDateTime() : void
    {
        $this->expectException(UnexpectedContentException::class);
        $this->createPageParser('<span class="article__header__date" content="wrong-time">')->parsePublishedDateTime();
    }

    public function testParseImageSrc() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('article_page_with_image.html'));
        $this->assertEquals(
            'https://s0.rbk.ru/v6_top_pics/resized/1180xH/media/img/0/72/755644792669720.jpg',
            $articleParser->parseImageSrc()
        );

        $articleParser = $this->createPageParser($this->loadHtmlFileContent('article_page.html'));
        $this->assertNull($articleParser->parseImageSrc());
    }

    public function testSetInvalidContent() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createPageParser('');
    }

    /**
     * @param string $content
     * @return ArticlePageParserInterface
     */
    private function createPageParser(string $content) : ArticlePageParserInterface
    {
        $parser = new RbcArticlePageParser(new Crawler());
        $parser->setContent($content);
        return $parser;
    }
}
