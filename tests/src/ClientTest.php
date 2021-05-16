<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\IngestionInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenInterface;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Client
 */
class ClientTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::createContainer
     */
    public function testContainer(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'http://example.com/search',
            'tokenApiEndpoint' => 'http://example.com/token',
            'consumerKey' => 'bar',
            'consumerSecret' => 'baz',
        ]);
        $container = $client->getContainer();

        // Check container services.
        $this->assertInstanceOf(OptionsResolver::class, $container->get('optionResolver'));
        $this->assertInstanceOf(HttpClientInterface::class, $container->get('httpClient'));
        $this->assertInstanceOf(RequestFactoryInterface::class, $container->get('requestFactory'));
        $this->assertInstanceOf(StreamFactoryInterface::class, $container->get('streamFactory'));
        $this->assertInstanceOf(UriFactoryInterface::class, $container->get('uriFactory'));
        $this->assertInstanceOf(MultipartStreamBuilder::class, $container->get('multipartStreamBuilder'));
        $this->assertInstanceOf(ReflectionExtractor::class, $container->get('reflectionExtractor'));
        $this->assertInstanceOf(GetSetMethodNormalizer::class, $container->get('getSetMethodNormalizer'));
        $this->assertInstanceOf(JsonEncoder::class, $container->get('jsonEncoder'));
        $this->assertInstanceOf(SerializerInterface::class, $container->get('serializer'));
        $this->assertInstanceOf(SearchInterface::class, $container->get('search'));
        $this->assertInstanceOf(TokenInterface::class, $container->get('token'));
        $this->assertInstanceOf(IngestionInterface::class, $container->get('ingestion'));
    }
}
