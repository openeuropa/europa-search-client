<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Exception\ParameterValueException;
use OpenEuropa\EuropaSearchClient\Model\Sort;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Model\Sort
 */
class SortTest extends TestCase
{
    /**
     * @covers ::setField
     * @covers ::getField
     * @covers ::setOrder
     * @covers ::getOrder
     */
    public function testSettersAndGetters(): void
    {
        $sort = (new Sort())
            ->setField('field1')
            ->setOrder('dEsC');

        $this->assertSame('field1', $sort->getField());
        $this->assertSame('DESC', $sort->getOrder());

        $sort->setOrder('asc');
        $this->assertSame('ASC', $sort->getOrder());

        $this->expectExceptionObject(
            new ParameterValueException(
                "::setOrder() received an invalid argument 'INVALID', must be one of 'ASC' and 'DESC'."
            )
        );
        $sort->setOrder('InVaLiD');
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testSerialization(): void
    {
        $sort = (new Sort())->setField('field1')->setOrder('dEsC');
        $this->assertSame('{"field":"field1","order":"DESC"}', json_encode($sort));
    }

    /**
     * @covers ::isEmpty
     */
    public function testIsEmpty(): void
    {
        $sort = (new Sort())->setField('field1')->setOrder('dEsC');
        $this->assertFalse($sort->isEmpty());
        $sort = (new Sort())->setField('field1');
        $this->assertTrue($sort->isEmpty());
        $sort = (new Sort())->setOrder('dEsC');
        $this->assertTrue($sort->isEmpty());
    }
}
