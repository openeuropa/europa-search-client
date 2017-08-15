<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\ProxyInterface.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Messages\RequestInterface;

/**
 * Interface MessageConverterInterface.
 *
 * Implementing this interface allows objects to transform a Message to send
 * the web service into a format usable by it and call the transporter
 * implementation for sending the result.
 *
 * @package EC\EuropaWS\Proxies
 */
interface MessageConverterInterface
{
    /**
     * Converts a message.
     *
     * @param ValidatableMessageInterface $message
     *   The message to convert.
     *
     * @return RequestInterface
     *   The converted message ready to be sent.
     */
    public function convertMessage(ValidatableMessageInterface $message);

    /**
     * Converts a message and integrated the converted components in it.
     *
     * @param ValidatableMessageInterface $message
     *   The message to convert.
     * @param array                       $convertedComponent
     *   Array of components that have already been converted in a
     *   previous process.
     *
     * @return RequestInterface
     *   The converted message (components included) ready to be sent.
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent);
}
