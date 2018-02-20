<?php

namespace OpenEuropa\EuropaSearch\Messages\Components;

use OpenEuropa\EuropaSearch\ConvertibleInterface;
use OpenEuropa\EuropaSearch\ValidatableInterface;

/**
 * Interface ComponentInterface.
 *
 * Implementing this interface allows object representing a message component.
 * Like messages themselves, they can be validated and transformed.
 *
 * @package OpenEuropa\EuropaWS\Messages\Components
 */
interface ComponentInterface extends ValidatableInterface, ConvertibleInterface
{

}
