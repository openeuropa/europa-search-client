<?php
/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\Clients\ClientDummy.
 */

namespace EC\EuropaWS\Tests\Dummies\Clients;

use EC\EuropaWS\Clients\ClientInterface;
use EC\EuropaWS\Messages\MessageInterface;

/**
 * Class ClientDummy.
 *
 * ClientInterface implementation for unit tests.
 *
 * @package EC\EuropaWS\Tests\Dummies\Clients
 */
class ClientDummy implements ClientInterface
{

    /**
     * {@inheritdoc}
     *
     * @internal Not implemented; used only for some unit tests.
     */
    public function sendMessage(MessageInterface $message)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }

    /**
     * {@inheritdoc}
     *
     * @internal Not implemented; used only for some unit tests.
     */
    public function validateMessage(MessageInterface $message)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }
}
