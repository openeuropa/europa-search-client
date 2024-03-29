<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetTestGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Model\FacetValue
 */
class FacetValueTest extends TestCase
{
    use FacetTestGeneratorTrait;

    public function testSettersAndGetters(): void
    {
        $facetValue = $this->generateTestingFacetValue([
            'apiVersion' => '2.31',
            'count' => 33,
            'rawValue' => 'very raw',
            'value' => 'whatever',
        ]);

        $this->assertSame('2.31', $facetValue->getApiVersion());
        $this->assertSame(33, $facetValue->getCount());
        $this->assertSame('very raw', $facetValue->getRawValue());
        $this->assertSame('whatever', $facetValue->getValue());
    }
}
