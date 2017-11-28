<?php

namespace EC\EuropaSearch\Messages\Components;

/**
 * Interface FilterQueryInterface.
 *
 * Implementing this interface allows object to be recognized as
 * component containing itself other components
 *
 * @package EC\EuropaSearch\Messages\Components
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
