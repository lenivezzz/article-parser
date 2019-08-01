<?php
declare(strict_types=1);

namespace php_part\resources;

use InvalidArgumentException;
use php_part\exceptions\NodeNotFoundException;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractPageParser
{
    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content) : void
    {
        if ($content === '') {
            throw new InvalidArgumentException('Content is empty');
        }
        $this->crawler->clear();
        $this->crawler->addHtmlContent($content);
    }

    /**
     * @param string $selector
     * @return Crawler
     * @throws NodeNotFoundException
     */
    protected function findNode(string $selector) : Crawler
    {
        $node = $this->crawler->filter($selector);
        if (!$node->count()) {
            throw NodeNotFoundException::create($selector, $this->crawler->html());
        }

        return $node;
    }
}
