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

        $proxies = [
            'messageProxy.messageDummy' => MessageConverterDummy::class,
            'componentProxy.componentDummy' => ComponentConverterDummy::class,
        ];

        foreach ($proxies as $key => $value) {
            $instance = $proxy->getConverterObject($key);
            $this->assertInstanceOf($value, $instance, 'The returned converter object is not a '.$value.' instance.');
        }
    }
}
