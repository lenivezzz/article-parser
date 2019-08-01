<?php
declare(strict_types=1);

namespace php_part\services;

use php_part\exceptions\InvalidArticleAttributesException;
use php_part\exceptions\NodeNotFoundException;
use php_part\exceptions\ParserFactoryException;
use php_part\exceptions\RepositoryException;
use php_part\parsers\ArticlePageParserFactoryInterface;
use php_part\repositories\ArticleRepositoryInterface;
use php_part\sourceproviders\WebResourceSourceProviderInterface;
use php_part\UrlProviderInterface;
use php_part\validators\ValidatorInterface;

class ArticleImportService implements ArticleImportInterface
{
    /**
     * @var UrlProviderInterface
     */
    private $urlProvider;
    /**
     * @var WebResourceSourceProviderInterface
     */
    private $webResourceSourceProvider;
    /**
     * @var ArticlePageParserFactoryInterface
     */
    private $parserFactory;
    /**
     * @var ArticleRepositoryInterface
     */
    private $articleRepository;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var array
     */
    private $logMessages = [];

    /**
     * @param UrlProviderInterface $urlProvider
     * @param WebResourceSourceProviderInterface $webResourceSourceProvider
     * @param ArticlePageParserFactoryInterface $articlePageParserFactory
     * @param ArticleRepositoryInterface $articleRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        UrlProviderInterface $urlProvider,
        WebResourceSourceProviderInterface $webResourceSourceProvider,
        ArticlePageParserFactoryInterface $articlePageParserFactory,
        ArticleRepositoryInterface $articleRepository,
        ValidatorInterface $validator
    ) {
        $this->urlProvider = $urlProvider;
        $this->webResourceSourceProvider = $webResourceSourceProvider;
        $this->parserFactory = $articlePageParserFactory;
        $this->articleRepository = $articleRepository;
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function import() : void
    {
        $urlList = $this->urlProvider->provideUrlList();
        foreach ($urlList as $url) {
            $this->logMessages[] = sprintf('%sProcessing %s', PHP_EOL, $url);

            try {
                $parser = $this->parserFactory->createByUrl($url);
                $parser->setContent($this->webResourceSourceProvider->provide($url));

                $attributes = [
                    'title' => $parser->parseTitle(),
                    'announce' => mb_substr(trim(strip_tags($parser->parseText())), 0, 200),
                    'content' => $parser->parseText(),
                    'image_src' => $parser->parseImageSrc(),
                    'published_at' => $parser->parsePublishedDateTime()->format('Y-m-d H:i:s'),
                    'hash' => sha1($url),
                ];
                $this->ensureArticleAttributesIsValid($attributes);

                $this->articleRepository->store($attributes);
            } catch (
                ParserFactoryException
                | NodeNotFoundException
                | RepositoryException
                | InvalidArticleAttributesException $e
            ) {
                $this->logMessages[] = sprintf('Failed to process url due reason:%s%s', PHP_EOL, $e->getMessage());
                continue;
            }

            $this->logMessages[] = 'Processed successfully';
        }
    }

    /**
     * @return array
     */
    public function getLogMessages() : array
    {
        return $this->logMessages;
    }

    /**
     * @param array $attributes
     */
    private function ensureArticleAttributesIsValid(array $attributes) : void
    {
        if ($this->validator->fails($attributes)) {
            throw InvalidArticleAttributesException::create($this->validator->getErrors());
        }
    }
}
