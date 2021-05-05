<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Token;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Token model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\Token
 */
class TokenTest extends TestCase
{
    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new Token();
        $model->setAccessToken('jwt');
        $model->setScope('am_application_scope default');
        $model->setTokenType('Bearer');
        $model->setExpiresIn(3600);

        $this->assertEquals('jwt', $model->getAccessToken());
        $this->assertEquals('am_application_scope default', $model->getScope());
        $this->assertEquals('Bearer', $model->getTokenType());
        $this->assertEquals(3600, $model->getExpiresIn());
    }
}
