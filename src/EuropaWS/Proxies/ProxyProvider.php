<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\ProxyProvider.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\ClientContainerFactory;
use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Messages\MessageInterface;

/**
 * Class ProxyProvider.
 *
 * It supplies the right Proxy class for treating a message or component.
 *
 * @package EC\EuropaWS\Proxies
 */
class ProxyProvider
{
    /**
     * The client container to use in the different method.
     *
     * @var \EC\EuropaWS\ClientContainer
     */
    private $factory;

    /**
     * Prefix for identifier of service used as message proxy.
     */
    const MESSAGE_ID_PREFIX = 'messageProxy.';

    /**
     * Prefix for identifier of service used as component proxy.
     */
    const COMPONENT_ID_PREFIX = 'componentProxy.';

    /**
     * ProxyProvider constructor.
     *
     * @param ClientContainerFactory $factory
     *   (The client container containing the client services
     *   configuration.
     */
    public function __construct(ClientContainerFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Gets the Proxy object needed to treat the message.
     *
     * @param MessageInterface $message
     *   The message to treat.
     *
     * @return ProxyInterface $proxy
     *   The proxy object that will treat the message.
     *
     * @throws ClientInstantiationException
     *  Raised if the proxy object is not retrieved.
     */
    public function getMessageProxy(MessageInterface $message)
    {

        try {
            $proxyServiceId = $message->getProxyIdentifier();

            return $this->factory->getClientContainer()->get($proxyServiceId);
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The message proxy has not been retrieved', $e);
        }
    }

    /**
     * Gets the Proxy object needed to treat the component.
     *
     * @param ComponentInterface $component
     *   The component to treat.
     *
     * @return ComponentProxyInterface $proxy
     *   The proxy object that will treat the component.
     *
     * @throws ClientInstantiationException
     *  Raised if the proxy object is not retrieved.
     */
    public function getComponentProxy(ComponentInterface $component)
    {

        try {
            $proxyServiceId = $component->getProxyIdentifier();

            return $this->factory->getClientContainer()->get($proxyServiceId);
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The component proxy has not been retrieved', $e);
        }
    }
}
