<?php
/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\Proxies\MessageProxyDummy
 */

namespace EC\EuropaWS\Tests\Dummies\Proxies;

use EC\EuropaWS\Messages\MessageInterface;
use EC\EuropaWS\Proxies\AbstractProxy;

/**
 * Class MessageProxyDummy
 *
 * AbstractProxy extension for unit tests.
 *
 * @package EC\EuropaWS\Tests\Dummies\Proxies
 */
class MessageProxyDummy extends AbstractProxy
{
    /**
     * {@inheritdoc}
     *
     * @internal Not implemented; used only for some unit tests.
     */
    public function communicateRequest(MessageInterface $request)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }
}
