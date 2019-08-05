<?php
declare(strict_types=1);

namespace php_part\services;

use php_part\exceptions\InvalidArticleAttributesException;
use php_part\exceptions\NodeNotFoundException;
use php_part\exceptions\ParserFactoryException;
use php_part\exceptions\RepositoryException;
use php_part\exceptions\WebPageSourceProviderException;
use php_part\resources\ArticlePageParserFactoryInterface;
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
        foreach ($this->loadUrlList() as $url) {
            $this->addLogMessage(sprintf('%sProcessing %s', PHP_EOL, $url));
            try {
                $this->importArticleFromUrl($url);
            } catch (WebPageSourceProviderException
                | ParserFactoryException
                | NodeNotFoundException
                | RepositoryException
                | InvalidArticleAttributesException $e
            ) {
                $this->addLogMessage(sprintf('Failed to process url due reason:%s%s', PHP_EOL, $e->getMessage()));
                continue;
            }

            $this->addLogMessage('Processed successfully');
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
     * @param string $message
     */
    private function addLogMessage(string $message) : void
    {
        $this->logMessages[] = $message;
    }

    /**
     * @param string $url
     */
    private function importArticleFromUrl(string $url) : void
    {
        $parser = $this->parserFactory->createByUrl($url);
        $parser->setContent($this->webResourceSourceProvider->provide($url));
        $this->storeArticle([
            'title' => $this->removeHtmlSpaces($parser->parseTitle()),
            'announce' => mb_substr($this->removeHtmlSpaces($parser->parseText()), 0, 200),
            'content' => $parser->parseText(),
            'image_src' => $parser->parseImageSrc(),
            'published_at' => $parser->parsePublishedDateTime()->format('Y-m-d H:i:s'),
            'hash' => $this->generateUrlHash($url),
        ]);
    }

    /**
     * @param array $attributes
     */
    private function storeArticle(array $attributes) : void
    {
        if ($this->validator->fails($attributes)) {
            throw InvalidArticleAttributesException::create($this->validator->getErrors());
        }
        $this->articleRepository->store($attributes);
    }

    /**
     * @param string $html
     * @return string
     */
    private function removeHtmlSpaces(string $html) : string
    {
        return str_replace("\t", '', trim(strip_tags($html)));
    }

    /**
     * @param string $url
     * @return string
     */
    private function generateUrlHash(string $url) : string
    {
        return sha1($url);
    }

    /**
     * @return array
     */
    private function loadUrlList() : array
    {
        $urlList = $this->urlProvider->provideUrlList();
        $this->removeProcessedUrls($urlList);
        return $urlList;
    }

    /**
     * @param array $urlList
     * @return void
     */
    private function removeProcessedUrls(array &$urlList) : void
    {
        $hashUrlList = [];
        foreach ($urlList as $url) {
            $hashUrlList[$this->generateUrlHash($url)] = $url;
        }

        $articleList = $this->articleRepository->findAllByHashList(array_keys($hashUrlList));
        foreach ($articleList->all() as $article) {
            unset($hashUrlList[$article->hash]);
        }

        $urlList = array_intersect($hashUrlList, $urlList);
    }
}
