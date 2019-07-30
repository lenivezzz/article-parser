<?php
declare(strict_types=1);

namespace php_part\parsers;

use DateTime;

interface ArticlePageParserInterface
{
    /**
     * @param string $content
     */
    public function setContent(string $content) : void;

    /**
     * @return string
     */
    public function parseTitle() : string;

    /**
     * @return string
     */
    public function parseText() : string;

    /**
     * @return DateTime
     */
    public function parsePublishedDateTime() : DateTime;

    /**
     * @return string
     */
    public function parseImageSrc() : ?string;
}
