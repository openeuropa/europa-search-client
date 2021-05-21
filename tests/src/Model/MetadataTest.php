<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Metadata;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Metadata model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\Metadata
 */
class MetadataTest extends TestCase
{
    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new Metadata();
        $model->setValue(['value1']);
        $model->setKey('key1');
        $this->assertEquals($model->getValue(), ['value1']);
        $this->assertEquals($model->getKey(), 'key1');

        $model = new Metadata();
        $model->setValue(['value3', 'value2']);
        $model->setKey('key3');

        $this->assertEquals($model->getValue(), ['value3', 'value2']);
        $this->assertEquals($model->getKey(), 'key3');
    }
}
