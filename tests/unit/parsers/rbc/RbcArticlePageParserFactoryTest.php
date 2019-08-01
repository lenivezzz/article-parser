<?php
declare(strict_types=1);

namespace tests\unit\parsers\rbc;

use php_part\exceptions\ParserFactoryException;
use php_part\parsers\rbc\RbcArticlePageParser;
use php_part\parsers\rbc\RbcArticlePageParserFactory;
use PHPUnit\Framework\TestCase;

class RbcArticlePageParserFactoryTest extends TestCase
{
    public function testCreateByUrl() : void
    {
        $factory = new RbcArticlePageParserFactory();

        $this->assertInstanceOf(
            RbcArticlePageParser::class,
            $factory->createByUrl('https://www.rbc.ru/someuri')
        );

        $this->expectException(ParserFactoryException::class);
        $this->assertInstanceOf(
            RbcArticlePageParser::class,
            $factory->createByUrl('https://invalid.url/someuri')
        );
    }
}