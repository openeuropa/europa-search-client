<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Api\SearchApi;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * @coversDefaultClass  \OpenEuropa\EuropaSearchClient\Api\ApiBase
 */
class ApiBaseTest extends TestCase
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

        $class = new \ReflectionClass(new SearchApi());
        $method = $class->getMethod('getConfigValue');
        $method->setAccessible(true);

        $this->assertSame('foo', $method->invokeArgs($container->get('search'), ['apiKey']));
        $this->assertSame('http://example.com/search', $method->invokeArgs($container->get('search'), ['searchApiEndpoint']));
        $this->expectExceptionObject(new \InvalidArgumentException("Invalid config key: 'foo'. Valid keys: 'apiKey', 'database', 'searchApiEndpoint'."));
        $method->invokeArgs($container->get('search'), ['foo']);
    }

    public function testMissingConfig(): void
    {
        $client = $this->getTestingClient();
        $class = new \ReflectionClass($client);
        $method = $class->getMethod('search');
        $method->setAccessible(true);
        $this->expectExceptionObject(new MissingOptionsException('The required options "apiKey", "database", "searchApiEndpoint" are missing.'));
        $this->assertSame([], $method->invokeArgs($client, []));
    }

    public function testInvalidEndpoint(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'database' => 'bar',
            'searchApiEndpoint' => 'INVALID_URL',
        ]);
        $this->expectExceptionObject(new InvalidOptionsException('The option "searchApiEndpoint" with value "INVALID_URL" is invalid.'));

        $container = $this->getClientContainer($client);
        $container->get('search');
    }
}
