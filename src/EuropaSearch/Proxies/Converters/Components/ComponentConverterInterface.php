<?php

namespace EC\EuropaSearch\Proxies\Converters\Components;

use EC\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Interface ComponentConverterInterface.
 *
 * Implementing this interface allows object to convert message components
 * in a format used by the transportation layer.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components
 */
interface ComponentConverterInterface
{

    /**
     * Converts a message component.
     *
     * @param \EC\EuropaSearch\Messages\Components\ComponentInterface $component
     *   The component to convert.
     *
     * @return array
     *   Array containing the conversion results.
     */
    public function convertComponent(ComponentInterface $component);
}
