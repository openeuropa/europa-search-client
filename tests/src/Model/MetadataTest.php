<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Metadata;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass  \OpenEuropa\EuropaSearchClient\Model\Metadata
 */
class MetadataTest extends TestCase
{
    /**
     * @covers ::setMetadata
     * @covers ::getMetadata
     */
    public function testSettersAndGetters(): void
    {
        $model = new Metadata();
        $model->setMetadata([
            'BODY_VALUE' => ["Value 1", "Value 2"],
        ]);
        $this->assertSame(['BODY_VALUE' => ['Value 1', 'Value 2']], $model->getMetadata());
        // Same but with array access.
        $this->assertSame(['Value 1', 'Value 2'], $model['BODY_VALUE']);

        $model->setMetadata([
            'BODY_VALUE' => ['Value 1', 'Value 2'],
            'TITLE' => ['Value 1'],
        ]);
        $this->assertSame([
            'BODY_VALUE' => ['Value 1', 'Value 2'],
            'TITLE' => ['Value 1'],
        ], $model->getMetadata());
        // Same but with array access.
        $this->assertSame(['Value 1', 'Value 2'], $model['BODY_VALUE']);
        $this->assertSame(['Value 1'], $model['TITLE']);
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testSerialization(): void
    {
        $model = new Metadata();
        $model->setMetadata([
            'BODY_VALUE' => ["Value 1", "Value 2"],
        ]);
        $actual = json_encode($model);
        $this->assertSame('{"BODY_VALUE":["Value 1","Value 2"]}', $actual);
    }

    /**
     * @covers ::validateOffset
     * @covers ::validateValue
     *
     * @dataProvider providerInvalidOffsetOrValue
     *
     * @param array $metadata
     * @param string $expectedExceptionMessage
     */
    public function testInvalidOffsetOrValue(array $metadata, string $expectedExceptionMessage): void
    {
        $model = new Metadata();
        $this->expectExceptionObject(new \InvalidArgumentException($expectedExceptionMessage));
        $model->setMetadata($metadata);
    }

    /**
     * @see self::testInvalidOffsetOrValue()
     * @return array[]
     */
    public function providerInvalidOffsetOrValue(): array
    {
        return [
            'non-string offset' => [
                [123 => []],
                'Passed offset should be a non-empty string. Given: 123',
            ],
            'empty string offset' => [
                ['' => []],
                "Passed offset should be a non-empty string. Given: ''",
            ],
            'non-array value' => [
                ['field1' => new \stdClass()],
                "The metadata 'field1' value should be an array. Given: (object) array(\n)",
            ],
            'array value with non-scalar' => [
                ['field1' => [new \stdClass()]],
                "The metadata 'field1' value, delta 0, should be a scalar. Given: (object) array(\n)",
            ],
        ];
    }
}
