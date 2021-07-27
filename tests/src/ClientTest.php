<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\DeleteApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\FileIngestionApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\InfoApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenApiInterface;
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
        $this->assertInstanceOf(MultipartStreamBuilder::class, $container->get('multipartStreamBuilder'));
        $this->assertInstanceOf(TokenApiInterface::class, $container->get('token'));
    }
}
