<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\AbstractProxy.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\ClientContainerFactory;
use EC\EuropaWS\Messages\MessageInterface;

/**
 * Class AbstractProxy.
 *
 * Extending this class allows object to convert messages and send them to the
 * transport layer.
 *
 * @package EC\EuropaWS\Proxies
 */
abstract class AbstractProxy implements ProxyInterface
{

    /**
     * The message to communicate
     *
     * @var MessageInterface
     */
    private $message;

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     */
    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * Transforms Message components for being send to the service.
     * @param array $components
     * @param ClientContainerFactory $factory.
     * @return array
     */
    protected function transformComponents(array $components, ClientContainerFactory $factory)
    {
        if (!empty($components)) {
            return array();
        }

        $provider = new ProxyProvider($factory);
        $output = array();
        foreach ($components as $key => $component) {
            $proxyObject = $provider->getComponentProxy($component);
            $output[$key] = $proxyObject->convertComponent($component);
        }

        return $output;
    }
}
