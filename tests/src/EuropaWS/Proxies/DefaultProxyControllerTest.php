<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\Proxies\BasicProxyControllerTest.
 */

namespace EC\EuropaWS\Tests\Proxies;

use EC\EuropaWS\ClientContainerFactory;
use EC\EuropaWS\Proxies\BasicProxyController;
use EC\EuropaWS\Tests\AbstractWSTest;
use EC\EuropaWS\Tests\Dummies\Messages\Components\ComponentDummy;
use EC\EuropaWS\Tests\Dummies\Messages\MessageDummy;
use EC\EuropaWS\Tests\Dummies\Proxies\ComponentConverterDummy;
use EC\EuropaWS\Tests\Dummies\Proxies\ComponentProxyDummy;
use EC\EuropaWS\Tests\Dummies\Proxies\MessageConverterDummy;
use EC\EuropaWS\Tests\Dummies\Proxies\MessageProxyDummy;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicProxyControllerTest.
 *
 * Tests the BasicProxyController behaviour.
 *
 * @package EC\EuropaWS\Tests\Clients
 */
class BasicProxyControllerTest extends AbstractWSTest
{

    /**
     * Tests the BasicProxyController registry to ensure the converter object are present.
     */
    public function testBasicProxyControllerRegistry()
    {

        $container = $this->getContainer();

        $proxy = $container->get('proxyController.default');

        // Tests the class type for the message converting object is the expected one.
        $messageConverter = $proxy->getConverterObject('messageProxy.messageDummy');
        $this->assertInstanceOf(
            MessageConverterDummy::class,
            $messageConverter,
            'The returned message converter object is not a MessageConverterDummy instance.'
        );

        // Tests the class type for the component converting object is the expected one.
        $componentConverter = $proxy->getConverterObject('componentProxy.componentDummy');
        $this->assertInstanceOf(
            ComponentConverterDummy::class,
            $componentConverter,
            'The returned component converter object is not a ComponentConverterDummy instance.'
        );
    }
}
