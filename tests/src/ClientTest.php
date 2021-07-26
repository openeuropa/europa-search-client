<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\DeleteApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\FileIngestionApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\InfoApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\TextIngestionApiInterface;
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

    public function testContainer(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'http://example.com/search',
            'facetApiEndpoint' => 'http://example.com/facet',
            'infoApiEndpoint' => 'http://example.com/search/info',
            'tokenApiEndpoint' => 'http://example.com/token',
            'consumerKey' => 'bar',
            'consumerSecret' => 'baz',
            'database' => 'qux',
            'textIngestionApiEndpoint' => 'http://example.com/ingest/text',
            'fileIngestionApiEndpoint' => 'http://example.com/ingest/file',
            'deleteApiEndpoint'  => 'http://example.com/ingest/delete',
        ]);
        $container = $client->getContainer();

        // Check container services.
        $this->assertInstanceOf(OptionsResolver::class, $container->get('optionResolver'));
        $this->assertInstanceOf(UriFactoryInterface::class, $container->get('uriFactory'));
        $this->assertInstanceOf(SearchApiInterface::class, $container->get('search'));
        $this->assertInstanceOf(FacetApiInterface::class, $container->get('facet'));
        $this->assertInstanceOf(InfoApiInterface::class, $container->get('info'));
        $this->assertInstanceOf(TokenApiInterface::class, $container->get('token'));
        $this->assertInstanceOf(TextIngestionApiInterface::class, $container->get('textIngestion'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('textIngestion'));
        $this->assertInstanceOf(FileIngestionApiInterface::class, $container->get('fileIngestion'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('fileIngestion'));
        $this->assertInstanceOf(DeleteApiInterface::class, $container->get('deleteDocument'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('deleteDocument'));
    }
}
