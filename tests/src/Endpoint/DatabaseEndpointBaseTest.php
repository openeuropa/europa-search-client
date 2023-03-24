<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Endpoint\DatabaseEndpointBase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Endpoint\DatabaseEndpointBase
 */
class DatabaseEndpointBaseTest extends TestCase
{
    public function testMissingConfig(): void
    {
        $this->expectExceptionObject(new MissingOptionsException('The required options "apiKey", "database" are missing.'));
        $this->getMockForAbstractClass(DatabaseEndpointBase::class, [
            'http://example.com/search',
        ]);
    }

    /**
     * @dataProvider providerTestInvalidConfig
     */
    public function testInvalidConfig($apiKey, $database, string $exceptionMessage): void
    {
        $this->expectExceptionObject(new InvalidOptionsException($exceptionMessage));
        $this->getMockForAbstractClass(DatabaseEndpointBase::class, [
            'http://example.com/search',
            [
                'apiKey' => $apiKey,
                'database' => $database,
            ],
        ]);
    }

    public function testDefinedConfig(): void
    {
        $this->expectExceptionObject(new UndefinedOptionsException('The option "foo" does not exist. Defined options are: "apiKey", "database", "endpointUrl".'));
        $this->getMockForAbstractClass(DatabaseEndpointBase::class, [
            'http://example.com/url',
            [
                'foo' => 'bar',
            ]
        ]);
    }

    public function providerTestInvalidConfig(): array
    {
        // The exception message is left unfinished to account for 2 versions
        // of the Symfony Options Resolver that use the word
        // "integer"(4) and "int"(5).
        return [
            'wrong "apiKey" format' => [
                1,
                'database',
                'The option "apiKey" with value 1 is expected to be of type "string", but is of type "int',
            ],
            'wrong "database" format' => [
                'apiKey',
                1,
                'The option "database" with value 1 is expected to be of type "string", but is of type "int',
            ],
        ];
    }
}
