<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\ComponentConverterInterface.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Interface ComponentConverterInterface.
 *
 * Implementing this interface allows object to convert message components
 * in a format used by the transportation layer.
 *
 * @package EC\EuropaWS\Proxies
 */
interface ComponentConverterInterface
{

    /**
     * Converts a message component.
     *
     * @param ComponentInterface $component
     *   The component to convert.
     *
     * @return array
     *   Array containing the conversion results.
     */
    public function convertComponent(ComponentInterface $component);
}
