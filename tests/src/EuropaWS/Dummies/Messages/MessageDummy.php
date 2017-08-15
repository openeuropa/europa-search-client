<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\Messages\MessageDummy.
 */

namespace EC\EuropaWS\Tests\Dummies\Messages;

use EC\EuropaWS\Messages\MessageInterface;
use EC\EuropaWS\Proxies\ProxyProvider;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class MessageDummy.
 *
 * MessageInterface implementation for unit tests.
 *
 * @package EC\EuropaWS\Tests\Dummies\Messages
 */
class MessageDummy implements MessageInterface
{

    /**
     * {@inheritdoc}
     *
     * @internal Not implemented; used only for some unit tests.
     */
    public function getComponents()
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }

    /**
     * {@inheritdoc}
     */
    public function getProxyIdentifier()
    {
        return ProxyProvider::MESSAGE_ID_PREFIX.'messageDummy';
    }

    /**
     * {@inheritdoc}
     *
     * @internal Not implemented; used only for some unit tests.
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }
}
