<?php
declare(strict_types=1);

namespace tests\unit\resources\rbc\parsers;

use PHPUnit\Framework\TestCase;
use RuntimeException;

abstract class AbstractHtmlPageParserTest extends TestCase
{
    /**
     * @param string $filename
     * @return string
     */
    protected function loadHtmlFileContent(string $filename) : string
    {
        $fullFilename = __DIR__ . '/../../../fixtures/htmlpages/rbc/' . $filename;

        if (!file_exists($fullFilename)) {
            throw new RuntimeException(sprintf('File %s not found', $fullFilename));
        }

        return file_get_contents($fullFilename);
    }
}
