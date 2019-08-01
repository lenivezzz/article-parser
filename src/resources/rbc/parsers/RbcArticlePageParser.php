<?php
declare(strict_types=1);

namespace php_part\resources\rbc\parsers;

use DateTime;
use php_part\exceptions\NodeNotFoundException;
use php_part\exceptions\UnexpectedContentException;
use php_part\resources\AbstractPageParser;
use php_part\resources\ArticlePageParserInterface;
use Throwable;

class RbcArticlePageParser extends AbstractPageParser implements ArticlePageParserInterface
{
    /**
     * @inheritDoc
     */
    public function parseTitle() : string
    {
        return trim($this->findNode('.article__header__title')->text());
    }

    /**
     * @inheritDoc
     */
    public function parseText() : string
    {
        return trim($this->findNode('.article__text')->html());
    }

    /**
     * @inheritDoc
     */
    public function parsePublishedDateTime() : DateTime
    {
        $time = $this->findNode('.article__header__date')->attr('content');
        try {
            return new DateTime($time);
        } catch (Throwable $e) {
            throw new UnexpectedContentException(sprintf('Unexpected time format: %s', $time));
        }
    }

    /**
     * @inheritDoc
     */
    public function parseImageSrc() : ?string
    {
        try {
            return $this->findNode('.article__main-image__image')->attr('src');
        } catch (NodeNotFoundException $e) {
            return null;
        }
    }
}
