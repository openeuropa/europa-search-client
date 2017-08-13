<?php

/**
 * @file
 * Contains EC\EuropaWS\Messages\MessageInterface.
 */

namespace EC\EuropaWS\Messages;

use EC\EuropaWS\ProxySubmissibleInterface;
use EC\EuropaWS\ValidatableInterface;

/**
 * Interface MessageInterface.
 *
 * It represents message objects submitted by a system to a web service.
 * Implementing this interface allows object to be validated and transformed by
 * the dedicated library mechanism.
 *
 * @package EC\EuropaWS\Messages
 */
interface MessageInterface extends ValidatableInterface, ProxySubmissibleInterface
{

    /**
     * Get message components
     *
     * @return array
     *   The array containing the message components.
     */
    public function getComponents();
}
