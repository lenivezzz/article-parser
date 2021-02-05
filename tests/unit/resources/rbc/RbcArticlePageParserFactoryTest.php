<?php
declare(strict_types=1);

namespace tests\unit\resources\rbc;

use php_part\exceptions\ParserFactoryException;
use php_part\resources\rbc\parsers\RbcArticlePageParser;
use php_part\resources\rbc\RbcArticlePageParserFactory;
use php_part\resources\rbc\parsers\RbcStyleArticlePageParser;
use PHPUnit\Framework\TestCase;

class RbcArticlePageParserFactoryTest extends TestCase
{
    public function testCreateByUrl() : void
    {
        $factory = new RbcArticlePageParserFactory();

        self::assertInstanceOf(
            RbcArticlePageParser::class,
            $factory->createByUrl('https://www.rbc.ru/someuri')
        );

        self::assertInstanceOf(
            RbcArticlePageParser::class,
            $factory->createByUrl('https://quote.rbc.ru/someuri')
        );

        $this->assertInstanceOf(
            RbcArticlePageParser::class,
            $factory->createByUrl('https://sport.rbc.ru/someuri')
        );

        $this->assertInstanceOf(
            RbcStyleArticlePageParser::class,
            $factory->createByUrl('https://style.rbc.ru/someuri')
        );

        $this->expectException(ParserFactoryException::class);
        $this->assertInstanceOf(
            RbcArticlePageParser::class,
            $factory->createByUrl('https://invalid.url/someuri')
        );
    }
}
