<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\FacetCollection;
use PHPUnit\Framework\TestCase;

/**
 * Tests the query facet model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\FacetCollectionOld
 */
class FacetCollectionTest extends TestCase
{

    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new FacetCollection();
        $facets = [
            'database' => 'database1',
            'count' => 0,
            'name' => 'name1',
            'rawName' => 'rawname1',
            'values' => []
        ];
        $model->addFacetdata('2.31', [$facets], 'term1');
        $expected = [
            'apiVersion' => '2.31',
            'facets' => [
                $facets
            ],
            'terms' => 'term1'
        ];

        $this->assertEquals($expected, $model->jsonSerialize());
        $facets = [
            [
            'database' => 'database1',
            'count' => 0,
            'name' => 'name1',
            'rawName' => 'rawname1',
            'values' => []
            ],
            [
                'database' => 'database2',
                'count' => 2,
                'name' => 'name2',
                'rawName' => 'rawname2',
                'values' => []
            ]
        ];
        $model->addFacetdata('2.31', [$facets], 'term2');
        $expected = [
            'apiVersion' => '2.31',
            'facets' => [
                $facets
            ],
            'terms' => 'term2'
        ];
        $this->assertEquals($expected, $model->jsonSerialize());
    }
}
