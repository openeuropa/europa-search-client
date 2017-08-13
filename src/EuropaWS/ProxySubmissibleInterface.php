<?php

/**
 * @file
 * Contains EC\EuropaWS\ProxySubmissibleInterface.
 */

namespace EC\EuropaWS;

/**
 * Interface ProxySubmissibleInterface.
 *
 * Implementing this interface allows an object to be transformed by the
 * Library mechanism into a format that can be sent to the targeted web
 * service.
 *
 * @package EC\EuropaWS
 */
interface ProxySubmissibleInterface
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
    public function getProxyIdentifier();
}
