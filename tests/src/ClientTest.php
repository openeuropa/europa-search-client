<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\DeleteEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\FacetEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\FileIngestionEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\InfoEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\TextIngestionEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenEndpointInterface;
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
        $this->assertInstanceOf(SearchEndpointInterface::class, $container->get('search'));
        $this->assertInstanceOf(FacetEndpointInterface::class, $container->get('facet'));
        $this->assertInstanceOf(InfoEndpointInterface::class, $container->get('info'));
        $this->assertInstanceOf(TokenEndpointInterface::class, $container->get('token'));
        $this->assertInstanceOf(TextIngestionEndpointInterface::class, $container->get('textIngestion'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('textIngestion'));
        $this->assertInstanceOf(FileIngestionEndpointInterface::class, $container->get('fileIngestion'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('fileIngestion'));
        $this->assertInstanceOf(DeleteEndpointInterface::class, $container->get('deleteDocument'));
        $this->assertInstanceOf(TokenAwareInterface::class, $container->get('deleteDocument'));
    }
}
