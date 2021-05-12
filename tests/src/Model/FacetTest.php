<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Facet;
use OpenEuropa\EuropaSearchClient\Model\FacetValue;
use PHPUnit\Framework\TestCase;

/**
 * Tests the facet model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\FacetValue
 */
class FacetTest extends TestCase
{

    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new FacetValue();
        $model->setApiVersion('2.31');
        $results = [
            new Facet(),
            new Facet(),
            new Facet(),
        ];
        $model->setFacets($results);
        $model->setTerms('***');

        $this->assertEquals('2.31', $model->getApiVersion());
        $this->assertEquals('***', $model->getTerms());
        $this->assertSame($results, $model->getFacets());
    }
}
