<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Endpoint\EndpointBase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @coversDefaultClass  \OpenEuropa\EuropaSearchClient\Endpoint\EndpointBase
 */
class EndpointBaseTest extends TestCase
{
    public function testEndpointUrlValidation(): void
    {
        $this->expectExceptionObject(new InvalidOptionsException('The option "endpointUrl" with value "INVALID_URL" is invalid.'));
        $this->getMockForAbstractClass(EndpointBase::class, [
            'INVALID_URL',
        ]);
    }

    /**
     * Tests that the base endpoint class doesn't expect any configuration but the endpoint URL.
     */
    public function testDefinedConfig(): void
    {
        $this->expectExceptionObject(new UndefinedOptionsException('The option "foo" does not exist. Defined options are: "endpointUrl".'));
        $this->getMockForAbstractClass(EndpointBase::class, [
            'http://example.com/search',
            [
                'foo' => 'bar',
            ]
        ]);
    }
}
