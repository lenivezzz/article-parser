<?php
declare(strict_types=1);

namespace php_part\resources\rbc;

interface RbcMainPageParserInterface
{
    /**
     * @param string $content
     */
    public function setContent(string $content) : void;

    /**
     * @return array
     */
    public function parseFeedUrlList() : array;
}
