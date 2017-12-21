<?php

namespace EC\EuropaSearch\Proxies\Converters;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Interface MessageConverterInterface.
 *
 * Implementing this interface allows objects to transform a Message to send
 * the web service into a format usable by it and call the transporter
 * implementation for sending the result.
 *
 * @package EC\EuropaSearch\Proxies\Converters
 */
interface MessageConverterInterface
{
    /**
     * Converts a message.
     *
     * @param \EC\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to convert.
     * @param \EC\EuropaSearch\EuropaSearchConfig                   $configuration
     *   The Web service configuration.
     *
     * @return \EC\EuropaSearch\Transporters\Requests\RequestInterface
     *   The converted message ready to be sent.
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration);

    /**
     * Converts a message and integrated the converted components in it.
     *
     * @param \EC\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to convert.
     * @param array                                                 $convertedComponent
     *   Array of components that have already been converted in a
     *   previous process.
     * @param \EC\EuropaSearch\EuropaSearchConfig                   $configuration
     *   The Web service configuration.
     *
     * @return \EC\EuropaSearch\Transporters\Requests\RequestInterface
     *   The converted message (components included) ready to be sent.
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, EuropaSearchConfig $configuration);

    /**
     * Converts a response received from the Transporter layer into a Message object.
     *
     * @param mixed                               $response
     *   The response received from the Transporter layer.
     * @param \EC\EuropaSearch\EuropaSearchConfig $configuration
     *   The Web service configuration.
     *
     * @return \EC\EuropaSearch\Messages\MessageInterface
     *   The obtained Message object.
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration);
}
