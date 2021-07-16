<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\QueryLanguage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OpenEuropa\EuropaSearchClient\Model\QueryLanguage
 */
class QueryLanguageTest extends TestCase
{

    public function testSettersAndGetters(): void
    {
        $model = new QueryLanguage();
        $model->setLanguage('EN');
        $model->setProbability(2.03);
        $this->assertEquals($model->getLanguage(), 'EN');
        $this->assertEquals($model->getProbability(), 2.03);
    }
}
