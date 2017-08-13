<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\ProxyInterface.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\Messages\MessageInterface;

/**
 * Interface ProxyInterface.
 *
 * Implementing this interface allows objects to transform a Message to send
 * the web service into a format usable by it and call the transporter
 * implementation for sending the result.
 *
 * @package EC\EuropaWS\Proxies
 */
interface ProxyInterface
{
    /**
     * Communicates the transformed message to the transporter to be sent.
     *
     * @param MessageInterface $request
     *   The request message to transform and to send.
     *
     * @return MessageInterface $response
     *   The response message received from the web service.
     */
    public function communicateRequest(MessageInterface $request);
}
