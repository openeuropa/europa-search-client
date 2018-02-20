<?php

namespace OpenEuropa\EuropaSearch;

/**
 * Interface ConvertibleInterface.
 *
 * Implementing this interface allows an object to be converted by the
 * library mechanism into a format that can be sent to the targeted web
 * service.
 *
 * @package OpenEuropa\EuropaSearch
 */
interface ConvertibleInterface
{
    /**
     * Gets the identifier of the proxy object.
     *
     * This proxy object behind this identifier will be called the
     * transformer mechanism in order to get the right format for sending
     * object data to the targeted web service.
     *
     * @return string
     *   The identifier.
     */
    public function getConverterIdentifier();
}
