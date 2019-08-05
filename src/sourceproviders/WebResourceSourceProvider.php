<?php
declare(strict_types=1);

namespace php_part\sourceproviders;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use php_part\exceptions\WebPageSourceProviderException;

class WebResourceSourceProvider implements WebResourceSourceProviderInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function provide(string $url) : string
    {
        try {
            $response = $this->client->request('get', $url);
        } catch (GuzzleException $e) {
            throw new WebPageSourceProviderException($e->getMessage(), 0, $e);
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new WebPageSourceProviderException(
                sprintf('Failed to load url %s. Http status: %s.', $url, $statusCode)
            );
        }

        return $response->getBody()->getContents();
    }
}
