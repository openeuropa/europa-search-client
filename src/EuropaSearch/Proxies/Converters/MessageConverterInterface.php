<?php

namespace OpenEuropa\EuropaSearch\Proxies\Converters;

use OpenEuropa\EuropaSearch\EuropaSearchConfig;
use OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Interface MessageConverterInterface.
 *
 * Implementing this interface allows objects to transform a Message to send
 * the web service into a format usable by it and call the transporter
 * implementation for sending the result.
 *
 * @package OpenEuropa\EuropaSearch\Proxies\Converters
 */
interface MessageConverterInterface
{
    /**
     * Converts a message.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to convert.
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig                   $configuration
     *   The Web service configuration.
     *
     * @return \OpenEuropa\EuropaSearch\Transporters\Requests\RequestInterface
     *   The converted message ready to be sent.
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration);

    /**
     * Converts a message and integrated the converted components in it.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to convert.
     * @param array                                                         $convertedComponent
     *   Array of components that have already been converted in a
     *   previous process.
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig                   $configuration
     *   The Web service configuration.
     *
     * @return \OpenEuropa\EuropaSearch\Transporters\Requests\RequestInterface
     *   The converted message (components included) ready to be sent.
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, EuropaSearchConfig $configuration);

    /**
     * Converts a response received from the Transporter layer into a Message object.
     *
     * @param mixed                                       $response
     *   The response received from the Transporter layer.
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig $configuration
     *   The Web service configuration.
     *
     * @return \OpenEuropa\EuropaSearch\Messages\MessageInterface
     *   The obtained Message object.
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration);
}
