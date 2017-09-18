<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\ProxyControllerInterface.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Exceptions\ProxyException;
use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Messages\MessageInterface;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Transporters\TransporterInterface;

/**
 * Interface ProxyControllerInterface.
 *
 * Implementing this interface allows objects to control the conversion of a Message to send
 * the web service into a format usable by it and call the transporter
 * implementation for sending the result.
 *
 * @package EC\EuropaWS\Proxies
 *
 * @package EC\EuropaWS\Proxies
 */
interface ProxyControllerInterface
{

    /**
     * Converts the message.
     *
     * @param MessageConverterInterface   $converter
     *   The converter to use.
     * @param ValidatableMessageInterface $message
     *   The message to convert.
     *
     * @return mixed
     *   The converted message.
     *
     * @throws ProxyException
     *   Raised if a problem occurred during the conversion process.
     */
    public function convertMessage(MessageConverterInterface $converter, ValidatableMessageInterface $message);

    /**
     * Converts the message and integrate their converted components in it.
     *
     * @param MessageConverterInterface   $converter
     *   The converter to use.
     * @param ValidatableMessageInterface $message
     *   The message to convert.
     * @param array                       $convertedComponent
     *   The list of converted message components to integrate.
     *
     * @return mixed
     *   The converted message.
     *
     * @throws ProxyException
     *   Raised if a problem occurred during the conversion process.
     */
    public function convertMessageWithComponents(MessageConverterInterface $converter, ValidatableMessageInterface $message, array $convertedComponent);

    /**
     * Converts a list of components.
     *
     * @param array $components
     *   The components to convert.
     *
     * @return array
     *   Array of the converted components or an empty one if the submitted
     *   list is empty.
     *
     * @throws ProxyException
     *   Raised if a problem occurred during the conversion process.
     */
    public function convertComponents(array $components);

    /**
     * Converts a component.
     *
     * @param ComponentConverterInterface $converter
     *   The converter to use.
     * @param ComponentInterface          $component
     *   The component to convert.
     *
     * @return mixed
     *   The converted component.
     *
     * @throws ClientInstantiationException
     *   Raised if the process failed because of the client instantiation problem.
     * @throws ProxyException
     *   Raised if a problem occured during the conversion process.
     */
    public function convertComponent(ComponentConverterInterface $converter, ComponentInterface $component);

    /**
     * Sends the request to the web service via the Transporter layer.
     *
     * @param MessageInterface     $message
     *   The message to send.
     * @param TransporterInterface $transporter
     *   The transporter in charge of the actual sending.
     *
     * @return MessageInterface
     *   The response from the service.
     *
     * @throws ConnectionException
     *   Raised if a connection problem occurred with the web service.
     * @throws WebServiceErrorException
     *   Raised if a problem occurred with the web service call. That can be an
     *   HTTP error or an error returned by the webd service itself.
     */
    public function sendRequest(MessageInterface $message, TransporterInterface $transporter);

    /**
     * Converts a web service response.
     *
     * @param MessageConverterInterface $converter
     *   The converter to use for the conversion.
     * @param mixed                     $response
     *   The response received from the Transporters layer
     *
     * @return MessageInterface
     *   The converted response usable by above layers.
     */
    public function convertResponse(MessageConverterInterface $converter, $response);

    /**
     * Gets a specific converter instances.
     *
     * @param string $convertId
     *   The converter registry id.
     *
     * @return mixed
     *   The converter instance.
     */
    public function getConverterObject($convertId);

    /**
     * Initializes the HTTP client configuration.
     *
     * @param WSConfigurationInterface $configuration
     *   The web service configuration for the initialization.
     */
    public function initProxy(WSConfigurationInterface $configuration);
}
