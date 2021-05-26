<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Facets;
use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetTestGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Model\Facets
 */
class FacetsTest extends TestCase
{
    use FacetTestGeneratorTrait;

    /**
     * @covers ::setApiVersion
     * @covers ::getApiVersion
     * @covers ::setFacets
     * @covers ::getFacets
     * @covers ::setTerms
     * @covers ::getTerms
     */
    public function testFacets(): void
    {
        [, $facetValuesAsObject] = $this->generateTestingFacetValues(5);
        $facets = (new Facets())
            ->setApiVersion('1.34-alpha3')
            ->setFacets($facetValuesAsObject)
            ->setTerms('foo');

        $this->assertSame('1.34-alpha3', $facets->getApiVersion());
        $this->assertSame($facetValuesAsObject, $facets->getFacets());
        $this->assertSame('foo', $facets->getTerms());
    }
}