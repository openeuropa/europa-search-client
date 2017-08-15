<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\Proxies\MessageConverterDummy
 */

namespace EC\EuropaWS\Tests\Dummies\Proxies;

use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Proxies\MessageConverterInterface;

/**
 * Class MessageConverterDummy
 *
 * MessageConverterInterface implementation for unit tests.
 *
 * @package EC\EuropaWS\Tests\Dummies\Proxies
 */
class MessageConverterDummy implements MessageConverterInterface
{
    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, WSConfigurationInterface $configuration)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, WSConfigurationInterface $configuration)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }
}
