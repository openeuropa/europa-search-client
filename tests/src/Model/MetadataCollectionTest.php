<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\MetadataCollection;
use PHPUnit\Framework\TestCase;

/**
 * Tests the query language model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\MetadataCollection
 */
class MetadataCollectionTest extends TestCase
{

    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new MetadataCollection();
        $model->addMetadata('key1', ['value1']);
        $expected = [
            'key1' => ['value1'],
        ];
        $this->assertEquals($expected, $model->jsonSerialize());

        $model->addMetadata('key2', ['value1', 'value2']);

        $expected = [
            'key1' => ['value1'],
            'key2' => ['value1', 'value2'],
        ];
        $this->assertEquals($expected, $model->jsonSerialize());
    }
}
