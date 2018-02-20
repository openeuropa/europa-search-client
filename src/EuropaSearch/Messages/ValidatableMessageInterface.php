<?php

namespace OpenEuropa\EuropaSearch\Messages;

use OpenEuropa\EuropaSearch\ConvertibleInterface;
use OpenEuropa\EuropaSearch\ValidatableInterface;

/**
 * Interface ValidatableMessageInterface.
 *
 * It represents message objects submitted by a system to a web service.
 * Implementing this interface allows object to be validated and transformed by
 * the dedicated library mechanism.
 *
 * @package OpenEuropa\EuropaSearch\Messages
 */
interface ValidatableMessageInterface extends MessageInterface, ValidatableInterface, ConvertibleInterface
{
    /**
     * Get message components.
     *
     * @return array
     *   The array containing the message components.
     */
    public function getComponents();
}
