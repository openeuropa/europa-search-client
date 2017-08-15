<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\ClientContainerTest.
 */

namespace EC\EuropaWS\Tests;

use EC\EuropaWS\Proxies\ProxyProvider;
use EC\EuropaWS\Tests\Dummies\ClientContainerFactoryDummy;
use EC\EuropaWS\Tests\Dummies\Clients\ClientDummy;
use EC\EuropaWS\Tests\Dummies\WSConfigurationDummy;
use EC\EuropaWS\Transporters\DummyTransporter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class ClientContainerTest.
 *
 * Tests the mechanism retrieving the ClientContainer.
 *
 * @package EC\EuropaWS\Test
 */
class ClientContainerTest extends AbstractWSTest
{
    /**
     * Tests that a services config is correctly retrieved in the container.
     */
    public function testClientContainerInstantiation()
    {
        $container = $this->getContainer();

        // Test Client instance.
        $client = $container->get('client.dummy');
        $this->assertInstanceOf(ClientDummy::class, $client, 'The returned client is not a ClientDummy instance.');

        // Test ProxyProvider.
        $proxy = $container->get('proxyProvider.default');
        $this->assertInstanceOf(ProxyProvider::class, $proxy, 'The returned proxy provider is not a ProxyProvider instance.');

        // Test Transporter.
        $transporter = $container->get('transporter.default');
        $this->assertInstanceOf(DummyTransporter::class, $transporter, 'The returned transporter is not a DummyTransporter instance.');

        // Test WS configuration.
        $settings = $container->get('ws.settings.default');
        $this->assertInstanceOf(WSConfigurationDummy::class, $settings, 'The returned settings object is not a WSConfigurationDummy instance.');
    }
}
