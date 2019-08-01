<?php
declare(strict_types=1);

namespace tests\unit\components;

use Faker\Factory;
use GuzzleHttp\ClientInterface;
use php_part\sourceproviders\WebResourceSourceProvider;
use php_part\exceptions\WebPageSourceProviderException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class WebPageSourceProviderTest extends TestCase
{
    public function testProvide() : void
    {
        $html = Factory::create()->randomHtml();
        $url = Factory::create()->url;

        $body = $this->getMockBuilder(StreamInterface::class)
            ->setMethods(['getContents'])
            ->getMockForAbstractClass();
        $body->method('getContents')->willReturn($html);

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->setMethods(['getStatusCode', 'getBody'])
            ->getMockForAbstractClass();
        $response->method('getStatusCode')->willReturn(200, 404);
        $response->method('getBody')->willReturn($body);

        /** @var ClientInterface|MockObject $client */
        $client = $this->getMockBuilder(ClientInterface::class)
            ->setMethods(['request'])
            ->getMockForAbstractClass();
        $client->method('request')->willReturn($response);

        $provider = new WebResourceSourceProvider($client);
        $this->assertEquals($html, $provider->provide($url));

        $this->expectException(WebPageSourceProviderException::class);
        $this->expectExceptionMessage(
            sprintf('Failed to load url %s. Http status: 404.', $url)
        );
        $provider->provide($url);
    }
}
