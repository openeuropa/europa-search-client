<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

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

    /**
     * @covers ::setConfiguration
     * @covers ::getConfigValue
     */
    public function testConfigResolver(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'http://example.com/search',
            // Config not defined in Search API schema.
            'foo' => 'bar',
        ]);
        $container = $client->getContainer();

        $class = new \ReflectionClass($container->get('search'));
        $method = $class->getMethod('getConfigValue');
        $method->setAccessible(true);

        $this->assertSame('foo', $method->invokeArgs($container->get('search'), ['apiKey']));
        $this->assertSame('http://example.com/search', $method->invokeArgs($container->get('search'), ['searchApiEndpoint']));
        $this->expectExceptionObject(new \InvalidArgumentException("Invalid config key: 'foo'. Valid keys: 'apiKey, searchApiEndpoint'."));
        $method->invokeArgs($container->get('search'), ['foo']);
    }

    /**
     * @covers ::setConfiguration
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
     * @covers ::setConfiguration
     */
    public function testInvalidEndpoint(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'INVALID_URL',
        ]);
        $this->expectExceptionObject(new InvalidOptionsException('The option "searchApiEndpoint" with value "INVALID_URL" is invalid.'));
        $client->getContainer()->get('search');
    }
}
