<?php
declare(strict_types=1);

namespace php_part\resources\rbc;

use php_part\sourceproviders\WebResourceSourceProviderInterface;
use php_part\UrlProviderInterface;

class RbcUrlProvider implements UrlProviderInterface
{
    /**
     * @var WebResourceSourceProviderInterface
     */
    private $sourceProvider;
    /**
     * @var RbcMainPageParserInterface
     */
    private $pageParser;

    /**
     * @param WebResourceSourceProviderInterface $sourceProvider
     * @param RbcMainPageParserInterface $pageParser
     */
    public function __construct(
        WebResourceSourceProviderInterface $sourceProvider,
        RbcMainPageParserInterface $pageParser
    ) {
        $this->sourceProvider = $sourceProvider;
        $this->pageParser = $pageParser;
    }

    /**
     * @return array
     */
    public function provideUrlList() : array
    {
        $source = $this->sourceProvider->provide('https://www.rbc.ru');
        $this->pageParser->setContent($source);
        return $this->pageParser->parseFeedUrlList();
    }
}
