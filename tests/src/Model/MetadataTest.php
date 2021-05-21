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
        $model->setCollection([
            'BODY_VALUE' => ["Value 1", "Value 2"],
        ]);
        $this->assertEquals($model->getCollection(), ['BODY_VALUE' => ["Value 1", "Value 2"]]);

        $model->setCollection([
            'BODY_VALUE' => ["Value 1", "Value 2"],
            'TITLE' => ["Value 1"],
        ]);
        $this->assertEquals($model->getCollection(), [
            'BODY_VALUE' => ["Value 1", "Value 2"],
            'TITLE' => ["Value 1"],
        ]);
    }

    /**
     * Tests JSON serialization.
     */
    public function testSerialization(): void
    {
        $model = new Metadata();
        $model->setCollection([
            'BODY_VALUE' => ["Value 1", "Value 2"],
        ]);
        $actual = json_encode($model);
        $this->assertEquals('{"BODY_VALUE":["Value 1","Value 2"]}', $actual);
    }
}
