<?php
declare(strict_types=1);

namespace tests\unit\resources\rbc\parsers;

use InvalidArgumentException;
use php_part\exceptions\NodeNotFoundException;
use php_part\exceptions\UnexpectedContentException;
use php_part\resources\ArticlePageParserInterface;
use php_part\resources\rbc\parsers\RbcStyleArticlePageParser;
use Symfony\Component\DomCrawler\Crawler;

class RbcStyleArticlePageParserTest extends AbstractHtmlPageParserTest
{
    public function testParseTitle() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('style_article_page.html'));
        $this->assertEquals('Культурные события августа: куда пойти', $articleParser->parseTitle());

        $this->expectException(NodeNotFoundException::class);
        $this->createPageParser('<div></div>')->parseTitle();
    }

    public function testParseText() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('style_article_page.html'));
        $this->assertEquals(
            '<p><span style="font-size:28px;">«Гив ми либерти»</span></p>',
            mb_substr($articleParser->parseText(), 0, 60)
        );

        $this->expectException(NodeNotFoundException::class);
        $this->createPageParser('<div></div>')->parseText();
    }

    public function testParsePublishDateTime() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('style_article_page.html'));
        $this->assertEquals('2019-08-01 19:46:58', $articleParser->parsePublishedDateTime()->format('Y-m-d H:i:s'));

        $this->expectException(NodeNotFoundException::class);
        $this->createPageParser('<div></div>')->parsePublishedDateTime();
    }

    public function testParseWrongPublishDateTime() : void
    {
        $this->expectException(UnexpectedContentException::class);
        $this->createPageParser('<div class="article__date" content="wrong-time">')->parsePublishedDateTime();
    }

    public function testParseImageSrc() : void
    {
        $articleParser = $this->createPageParser($this->loadHtmlFileContent('style_article_page.html'));
        $this->assertEquals(
            'https://s0.rbk.ru/v6_top_pics/resized/960x620_crop/media/img/3/94/755646683376943.png',
            $articleParser->parseImageSrc()
        );
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
        $parser = new RbcStyleArticlePageParser(new Crawler());
        $parser->setContent($content);
        return $parser;
    }
}
