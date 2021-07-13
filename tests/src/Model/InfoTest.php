<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Info;
use PHPUnit\Framework\TestCase;

/**
 * Tests the info model class.
 */
class InfoTest extends TestCase
{
    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $info = new Info();
        $info->setGroupId('eu.europa.ec.digit.search.webapp');
        $info->setComment('version=2.69');
        $info->setArtifactId('search-api');

        $this->assertSame('eu.europa.ec.digit.search.webapp', $info->getGroupId());
        $this->assertSame('version=2.69', $info->getComment());
        $this->assertSame('search-api', $info->getArtifactId());
    }
}
