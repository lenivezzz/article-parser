<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use php_part\parsers\rbc\RbcArticlePageParserFactory;
use php_part\parsers\rbc\RbcMainPageParser;
use php_part\parsers\rbc\RbcUrlProvider;
use php_part\repositories\ArticleDbRepository;
use php_part\services\ArticleImportService;
use php_part\sourceproviders\WebResourceSourceProvider;
use php_part\validators\ArticleValidator;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Validation;

require __DIR__ . '/../bootstrap.php';

$client = new Client();

$service = new ArticleImportService(
    new RbcUrlProvider(
        new WebResourceSourceProvider($client),
        new RbcMainPageParser(new Crawler())
    ),
    new WebResourceSourceProvider($client),
    new RbcArticlePageParserFactory(),
    new ArticleDbRepository(),
    new ArticleValidator(Validation::createValidator())
);

$service->import();

echo implode(PHP_EOL, $service->getLogMessages());
