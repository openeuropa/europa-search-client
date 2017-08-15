<?php

/**
 * @file
 * Contains EC\EuropaWS\Messages\ValidatableMessageInterface\ComponentInterface.
 */

namespace EC\EuropaWS\Messages\Components;

use EC\EuropaWS\ConvertibleInterface;
use EC\EuropaWS\ValidatableInterface;

/**
 * Interface ComponentInterface.
 *
 * Implementing this interface allows object representing a message component.
 * As messages themselves, they can be validated and transformed.
 *
 * @package EC\EuropaWS\Messages\Components
 */
interface ComponentInterface extends ValidatableInterface, ConvertibleInterface
{

}
