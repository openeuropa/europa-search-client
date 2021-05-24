<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetTestGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Model\Facet
 */
class FacetTest extends TestCase
{
    use FacetTestGeneratorTrait;

    /**
     * @covers ::setApiVersion
     * @covers ::getApiVersion
     * @covers ::setCount
     * @covers ::getCount
     * @covers ::setDatabase
     * @covers ::getDatabase
     * @covers ::setName
     * @covers ::getName
     * @covers ::setRawName
     * @covers ::getRawName
     * @covers ::setValues
     * @covers ::getValues
     */
    public function testSettersAndGetters(): void
    {
        [$facetValuesAsArray, $facetValuesAsObject] = $this->generateTestingFacetValues(5);
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
