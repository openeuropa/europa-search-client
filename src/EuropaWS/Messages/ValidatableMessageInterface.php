<?php

/**
 * @file
 * Contains EC\EuropaWS\Messages\ValidatableMessageInterface.
 */

namespace EC\EuropaWS\Messages;

use EC\EuropaWS\ConvertibleInterface;
use EC\EuropaWS\ValidatableInterface;

/**
 * Interface ValidatableMessageInterface.
 *
 * It represents message objects submitted by a system to a web service.
 * Implementing this interface allows object to be validated and transformed by
 * the dedicated library mechanism.
 *
 * @package EC\EuropaWS\Messages
 */
interface ValidatableMessageInterface extends MessageInterface, ValidatableInterface, ConvertibleInterface
{

    /**
     * Get message components
     *
     * @return array
     *   The array containing the message components.
     */
    public function getComponents();
}
