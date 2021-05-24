<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenApiInterface;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
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
            'facetApiEndpoint' => 'http://example.com/facet',
            'tokenApiEndpoint' => 'http://example.com/token',
            'consumerKey' => 'bar',
            'consumerSecret' => 'baz',
            'database' => 'qux',
            'textIngestionApiEndpoint' => 'http://example.com/ingest/text',
            'deleteApiEndpoint'  => 'http://example.com/ingest/delete',
        ]);
        $container = $client->getContainer();

        // Check container services.
        $this->assertInstanceOf(OptionsResolver::class, $container->get('optionResolver'));
        $this->assertInstanceOf(HttpClientInterface::class, $container->get('httpClient'));
        $this->assertInstanceOf(RequestFactoryInterface::class, $container->get('requestFactory'));
        $this->assertInstanceOf(StreamFactoryInterface::class, $container->get('streamFactory'));
        $this->assertInstanceOf(UriFactoryInterface::class, $container->get('uriFactory'));
        $this->assertInstanceOf(MultipartStreamBuilder::class, $container->get('multipartStreamBuilder'));
        $this->assertInstanceOf(JsonEncoder::class, $container->get('jsonEncoder'));
        $this->assertInstanceOf(SerializerInterface::class, $container->get('serializer'));
        $this->assertInstanceOf(SearchApiInterface::class, $container->get('search'));
        $this->assertInstanceOf(FacetApiInterface::class, $container->get('facet'));
        $this->assertInstanceOf(TokenApiInterface::class, $container->get('token'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('textIngestion'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('deleteDocument'));
    }
}
