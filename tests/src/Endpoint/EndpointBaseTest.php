<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * @coversDefaultClass  \OpenEuropa\EuropaSearchClient\Endpoint\EndpointBase
 */
class EndpointBaseTest extends TestCase
{
    use ClientTestTrait;

    public function testConfigResolver(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'database' => 'bar',
            'searchApiEndpoint' => 'http://example.com/search',
            // Config not defined in Search API schema.
            'foo' => 'bar',
        ]);
        $container = $this->getClientContainer($client);

        $class = new \ReflectionClass($container->get('search'));
        $method = $class->getMethod('getConfigValue');
        $method->setAccessible(true);

        $this->assertSame('foo', $method->invokeArgs($container->get('search'), ['apiKey']));
        $this->assertSame('http://example.com/search', $method->invokeArgs($container->get('search'), ['endpointUrl']));
        $this->expectExceptionObject(new \InvalidArgumentException("Invalid config key: 'foo'. Valid keys: 'apiKey', 'database', 'endpointUrl'."));
        $method->invokeArgs($container->get('search'), ['foo']);
    }

    public function testMissingConfig(): void
    {
        $client = $this->getTestingClient([
            'searchApiEndpoint' => '',
        ]);
        $this->expectExceptionObject(new MissingOptionsException('The required options "apiKey", "database" are missing.'));
        $client->search();
    }

    public function testInvalidEndpoint(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'database' => 'bar',
            'searchApiEndpoint' => 'INVALID_URL',
        ]);
        $this->expectExceptionObject(new InvalidOptionsException('The option "endpointUrl" with value "INVALID_URL" is invalid.'));
        $client->search();
    }
}
