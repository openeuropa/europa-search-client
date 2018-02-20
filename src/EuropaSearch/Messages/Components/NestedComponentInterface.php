<?php

namespace OpenEuropa\EuropaSearch\Messages\Components;

/**
 * Interface FilterQueryInterface.
 *
 * Implementing this interface allows object to be recognized as
 * component containing itself other components
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components
 */
interface NestedComponentInterface extends ComponentInterface
{
    /**
     * Gets component children.
     *
     * @return array
     *   The components contained by the current component.
     */
    public function getChildComponents();
}
