<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\ClientContainerTest.
 */

namespace EC\EuropaWS\Tests;

use EC\EuropaWS\Common\DefaultValidatorBuilder;
use EC\EuropaWS\Proxies\BasicProxyController;
use EC\EuropaWS\Tests\Dummies\Clients\ClientDummy;
use EC\EuropaWS\Tests\Dummies\WSConfigurationDummy;
use EC\EuropaWS\Transporters\DummyTransporter;

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

        $instances = [
            'client.dummy' => ClientDummy::class,
            'proxyController.default' => BasicProxyController::class,
            'transporter.default' => DummyTransporter::class,
            'ws.settings.default' => WSConfigurationDummy::class,
            'validator.default' => DefaultValidatorBuilder::class,
        ];

        foreach ($instances as $key => $value) {
            $instance = $container->get($key);
            $this->assertInstanceOf($value, $instance, 'The returned settings object is not a '.$value.' instance.');
        }
    }
}
