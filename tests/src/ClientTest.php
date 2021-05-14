<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Api\Ingestion;
use OpenEuropa\EuropaSearchClient\Contract\SearchInterface;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
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
     * @covers \OpenEuropa\EuropaSearchClient\Traits\ServicesTrait::getConfiguration
     */
    public function testContainerAndConfig(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'http://example.com/search'
        ]);
        $container = $client->getContainer();

        // Check container services.
        $this->assertInstanceOf(HttpClientInterface::class, $container->get('httpClient'));
        $this->assertInstanceOf(RequestFactoryInterface::class, $container->get('requestFactory'));
        $this->assertInstanceOf(StreamFactoryInterface::class, $container->get('streamFactory'));
        $this->assertInstanceOf(UriFactoryInterface::class, $container->get('uriFactory'));
        $this->assertInstanceOf(MultipartStreamBuilder::class, $container->get('multipartStreamBuilder'));
        $this->assertInstanceOf(ReflectionExtractor::class, $container->get('reflectionExtractor'));
        $this->assertInstanceOf(GetSetMethodNormalizer::class, $container->get('getSetMethodNormalizer'));
        $this->assertInstanceOf(JsonEncoder::class, $container->get('jsonEncoder'));
        $this->assertInstanceOf(SerializerInterface::class, $container->get('serializer'));
        $this->assertInstanceOf(OptionsResolver::class, $container->get('optionResolver'));
        $this->assertInstanceOf(SearchInterface::class, $container->get('searchApi'));
        $this->assertInstanceOf(Ingestion::class, $container->get('ingestionApi'));

        // Check that API services are able to access the container.
        $this->assertSame($container, $container->get('searchApi')->getContainer());
        $this->assertSame($container, $container->get('ingestionApi')->getContainer());

        // Check configuration.
        $class = new \ReflectionClass($client);
        $method = $class->getMethod('getConfiguration');
        $method->setAccessible(true);
        $this->assertSame([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'http://example.com/search',
        ], $method->invokeArgs($client, []));
    }

    /**
     * @covers ::getSearch
     * @covers ::getHttpClient
     * @covers ::getRequestFactory
     * @covers ::getStreamFactory
     * @covers ::getUriFactory
     * @covers ::getMultipartStreamBuilder
     * @covers ::getSerializer
     * @covers ::getJsonEncoder
     * @covers ::getOptionResolver
     *
     * @dataProvider providerTestGetters
     */
    public function testGetters(string $methodName, string $interface): void
    {
        $client = $this->getTestingClient();
        $class = new \ReflectionClass($client);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        $this->assertInstanceOf($interface, $method->invokeArgs($client, []));
    }

    /**
     * @see self::testGetters()
     */
    public function providerTestGetters(): array
    {
        return [
            ['getSearch', SearchInterface::class],
            ['getHttpClient', HttpClientInterface::class],
            ['getRequestFactory', RequestFactoryInterface::class],
            ['getStreamFactory', StreamFactoryInterface::class],
            ['getUriFactory', UriFactoryInterface::class],
            ['getMultipartStreamBuilder', MultipartStreamBuilder::class],
            ['getSerializer', SerializerInterface::class],
            ['getJsonEncoder', EncoderInterface::class],
            ['getOptionResolver', Options::class],
        ];
    }

    /**
     * @covers ::getConfiguration
     */
    public function testMissingConfig(): void
    {
        $client = $this->getTestingClient();
        $class = new \ReflectionClass($client);
        $method = $class->getMethod('search');
        $method->setAccessible(true);
        $this->expectExceptionObject(new MissingOptionsException('The required options "apiKey", "searchApiEndpoint" are missing.'));
        $this->assertSame([], $method->invokeArgs($client, []));
    }

    /**
     * @covers ::getConfiguration
     */
    public function testUndefinedConfig(): void
    {
        $client = $this->getTestingClient(['foo' => 'bar']);
        $class = new \ReflectionClass($client);
        $method = $class->getMethod('search');
        $method->setAccessible(true);
        $this->expectExceptionObject(new UndefinedOptionsException('The option "foo" does not exist. Defined options are: "apiKey", "searchApiEndpoint".'));
        $this->assertSame([], $method->invokeArgs($client, []));
    }
}
