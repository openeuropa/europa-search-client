<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetTestGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * Tests the facet model class.
 */
class FacetTest extends TestCase
{
    use FacetTestGeneratorTrait;

    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $facetValuesAsArray = $this->generateTestingFacetValuesAsArray(5);
        $facetValuesAsObject = $this->generateTestingFacetValuesAsObject($facetValuesAsArray);
        $facet = $this->generateTestingFacet([
            'apiVersion' => '1.34',
            'count' => 123,
            'database' => 'foo',
            'name' => 'My name',
            'rawName' => 'rusty',
            'values' => $facetValuesAsArray,
        ]);

        $this->assertSame('1.34', $facet->getApiVersion());
        $this->assertSame(123, $facet->getCount());
        $this->assertSame('foo', $facet->getDatabase());
        $this->assertSame('My name', $facet->getName());
        $this->assertSame('rusty', $facet->getRawName());
        $this->assertEquals($facetValuesAsObject, $facet->getValues());
    }
}
