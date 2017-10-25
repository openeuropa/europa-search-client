<?php

namespace EC\EuropaSearch\Proxies\Converters;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Messages\MessageInterface;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;
use EC\EuropaSearch\Transporters\Requests\RequestInterface;

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
     * @param ValidatableMessageInterface $message
     *   The message to convert.
     * @param EuropaSearchConfig          $configuration
     *   The Web service configuration.
     *
     * @return RequestInterface
     *   The converted message ready to be sent.
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration);

    /**
     * Converts a message and integrated the converted components in it.
     *
     * @param ValidatableMessageInterface $message
     *   The message to convert.
     * @param array                       $convertedComponent
     *   Array of components that have already been converted in a
     *   previous process.
     * @param EuropaSearchConfig          $configuration
     *   The Web service configuration.
     *
     * @return RequestInterface
     *   The converted message (components included) ready to be sent.
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, EuropaSearchConfig $configuration);

    /**
     * Converts a response received from the Transporter layer into a Message object.
     *
     * @param mixed              $response
     *   The response received from the Transporter layer.
     * @param EuropaSearchConfig $configuration
     *   The Web service configuration.
     *
     * @return MessageInterface
     *   The obtained Message object.
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration);
}
