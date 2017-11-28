<?php

namespace EC\EuropaSearch\Messages\Components;

use EC\EuropaSearch\ConvertibleInterface;
use EC\EuropaSearch\ValidatableInterface;

/**
 * Interface ComponentInterface.
 *
 * Implementing this interface allows object representing a message component.
 * Like messages themselves, they can be validated and transformed.
 *
 * @package EC\EuropaWS\Messages\Components
 */
interface ComponentInterface extends ValidatableInterface, ConvertibleInterface
{

}
