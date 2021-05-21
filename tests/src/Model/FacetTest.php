<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Facet;
use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetValueTestGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Model\Facet
 */
class FacetTest extends TestCase
{
    use FacetValueTestGeneratorTrait;

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
        $facetValues = [];
        for ($i = 0; $i < 5; $i++) {
            $facetValues[] = $this->generateTestingFacetValue([
                'apiVersion' => str_shuffle(md5(serialize($facetValues))),
                'count' => rand(30, 3000),
                'rawValue' => str_shuffle(md5(serialize($facetValues))),
                'value' => str_shuffle(md5(serialize($facetValues))),
            ]);
        }

        $facet = (new Facet())
            ->setApiVersion('1.34')
            ->setCount(123)
            ->setDatabase('foo')
            ->setName('My name')
            ->setRawName('rusty')
            ->setValues($facetValues);

        $this->assertSame('1.34', $facet->getApiVersion());
        $this->assertSame(123, $facet->getCount());
        $this->assertSame('foo', $facet->getDatabase());
        $this->assertSame('My name', $facet->getName());
        $this->assertSame('rusty', $facet->getRawName());
        $this->assertSame($facetValues, $facet->getValues());
    }
}
