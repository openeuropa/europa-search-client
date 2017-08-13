<?php
/**
 * @file
 * Contains EC\EuropaWS\Tests\Proxies\ProxyProviderTest.
 */

namespace EC\EuropaWS\Tests\Proxies;

use EC\EuropaWS\ClientContainerFactory;
use EC\EuropaWS\Proxies\ProxyProvider;
use EC\EuropaWS\Tests\Dummies\Messages\Components\ComponentDummy;
use EC\EuropaWS\Tests\Dummies\Messages\MessageDummy;
use EC\EuropaWS\Tests\Dummies\Proxies\ComponentProxyDummy;
use EC\EuropaWS\Tests\Dummies\Proxies\MessageProxyDummy;
use PHPUnit\Framework\TestCase;

/**
 * Class ProxyProviderTest.
 *
 * Tests the ProxyProvider behaviour.
 *
 * @package EC\EuropaWS\Tests\Clients
 */
class ProxyProviderTest extends TestCase
{

    /**
     * Tests it returns the excepted AbstractProxy extension.
     */
    public function testReturnedMessageProxy()
    {
        $factory = new ClientContainerFactory();
        $provider = new ProxyProvider($factory);

        $messageProxy = $provider->getMessageProxy(new MessageDummy());

        $this->assertInstanceOf(MessageProxyDummy::class, $messageProxy, 'The returned proxy is not a ProxyDummy instance.');
    }

    /**
     * Tests it returns the right ComponentProxyInterface implementation.
     */
    public function testReturnedComponentProxy()
    {
        $factory = new ClientContainerFactory();
        $provider = new ProxyProvider($factory);

        $componentProxy = $provider->getComponentProxy(new ComponentDummy());

        $this->assertInstanceOf(ComponentProxyDummy::class, $componentProxy, 'The returned proxy is not a ComponentProxyDummy instance.');
    }
}
