<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\Clients\ClientProviderTest.
 */

namespace EC\EuropaWS\Tests\Clients;

use EC\EuropaWS\ClientContainerFactory;
use EC\EuropaWS\Clients\ClientProvider;
use EC\EuropaWS\Tests\Dummies\Clients\ClientDummy;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientProviderTest.
 *
 * Tests the ClientProvider behaviour.
 *
 * @package EC\EuropaWS\Tests\Clients
 */
class ClientProviderTest extends TestCase
{
    /**
     * Tests it returns the excepted ClientInterface implementation.
     */
    public function testReturnedClient()
    {
        $factory = new ClientContainerFactory();
        $provider = new ClientProvider($factory);

        $client = $provider->getClient('client.dummy');

        $this->assertInstanceOf(ClientDummy::class, $client, 'The returned client is not a ClientDummy instance.');
    }
}
