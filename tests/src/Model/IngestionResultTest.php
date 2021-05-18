<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\IngestionResult;
use PHPUnit\Framework\TestCase;

/**
 * Tests the ingestion model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\IngestionResult
 */
class IngestionResultTest extends TestCase
{

    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new IngestionResult();
        $model->setApiVersion('2.31');
        $model->setReference('a4676974-39a6-4d72-a054-eede794b30d6');
        $model->setTrackingId('d426d72b-3b2c-4207-92f9-0f813934221f');

        $this->assertEquals('2.31', $model->getApiVersion());
        $this->assertEquals('a4676974-39a6-4d72-a054-eede794b30d6', $model->getReference());
        $this->assertEquals('d426d72b-3b2c-4207-92f9-0f813934221f', $model->getTrackingId());
    }
}
