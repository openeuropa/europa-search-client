<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Endpoint\DeleteEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\FacetEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\FileIngestionEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\InfoEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\SearchEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\TextIngestionEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\TokenEndpoint;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $container = $this->getClientContainer($client);

        // Check container services.
        $this->assertInstanceOf(OptionsResolver::class, $container->get('optionResolver'));
        $this->assertInstanceOf(MultipartStreamBuilder::class, $container->get('multipartStreamBuilder'));
        $this->assertInstanceOf(SearchEndpoint::class, $container->get('search'));
        $this->assertInstanceOf(FacetEndpoint::class, $container->get('facet'));
        $this->assertInstanceOf(InfoEndpoint::class, $container->get('info'));
        $this->assertInstanceOf(TokenEndpoint::class, $container->get('token'));
        $this->assertInstanceOf(TextIngestionEndpoint::class, $container->get('textIngestion'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('textIngestion'));
        $this->assertInstanceOf(FileIngestionEndpoint::class, $container->get('fileIngestion'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('fileIngestion'));
        $this->assertInstanceOf(DeleteEndpoint::class, $container->get('deleteDocument'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('deleteDocument'));
    }
}
