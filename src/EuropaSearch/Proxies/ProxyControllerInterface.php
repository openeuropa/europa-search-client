<?php

namespace OpenEuropa\EuropaSearch\Proxies;

use OpenEuropa\EuropaSearch\EuropaSearchConfig;
use OpenEuropa\EuropaSearch\Exceptions\WebServiceErrorException;
use OpenEuropa\EuropaSearch\Messages\Components\ComponentInterface;
use OpenEuropa\EuropaSearch\Messages\MessageInterface;
use OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface;
use OpenEuropa\EuropaSearch\Proxies\Converters\Components\ComponentConverterInterface;
use OpenEuropa\EuropaSearch\Proxies\Converters\MessageConverterInterface;
use OpenEuropa\EuropaSearch\Transporters\TransporterInterface;

/**
 * Interface ProxyControllerInterface.
 *
 * Implementing this interface allows objects to control the conversion of a Message to send
 * the web service into a format usable by it and call the transporter
 * implementation for sending the result.
 *
 * @package OpenEuropa\EuropaSearch\Proxies
 */
interface ProxyControllerInterface
{
    /**
     * Converts the message.
     *
     * @param \OpenEuropa\EuropaSearch\Proxies\Converters\MessageConverterInterface $converter
     *   The converter to use.
     * @param \OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface         $message
     *   The message to convert.
     *
     * @return mixed
     *   The converted message.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ProxyException
     *   Raised if a problem occurred during the conversion process.
     */
    public function convertMessage(MessageConverterInterface $converter, ValidatableMessageInterface $message);

    /**
     * Converts the message and integrate their converted components in it.
     *
     * @param \OpenEuropa\EuropaSearch\Proxies\Converters\MessageConverterInterface $converter
     *   The converter to use.
     * @param \OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface         $message
     *   The message to convert.
     * @param array                                                         $convertedComponent
     *   The list of converted message components to integrate.
     *
     * @return mixed
     *   The converted message.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ProxyException
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
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ProxyException
     *   Raised if a problem occurred during the conversion process.
     */
    public function convertComponents(array $components);

    /**
     * Converts a component.
     *
     * @param \OpenEuropa\EuropaSearch\Proxies\Converters\Components\ComponentConverterInterface $converter
     *   The converter to use.
     * @param \OpenEuropa\EuropaSearch\Messages\Components\ComponentInterface                    $component
     *   The component to convert.
     *
     * @return mixed
     *   The converted component.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException
     *   Raised if the process failed because of the client instantiation problem.
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ProxyException
     *   Raised if a problem occured during the conversion process.
     */
    public function convertComponent(ComponentConverterInterface $converter, ComponentInterface $component);

    /**
     * Sends the request to the web service via the Transporter layer.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\MessageInterface $message
     *   The message to send.
     * @param TransporterInterface                       $transporter
     *   The transporter in charge of the actual sending.
     *
     * @return \OpenEuropa\EuropaSearch\Messages\MessageInterface
     *   The response from the service.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ConnectionException
     *   Raised if a connection problem occurred with the web service.
     * @throws WebServiceErrorException
     *   Raised if a problem occurred with the web service call. That can be an
     *   HTTP error or an error returned by the webd service itself.
     */
    public function sendRequest(MessageInterface $message, TransporterInterface $transporter);

    /**
     * Converts a web service response.
     *
     * @param \OpenEuropa\EuropaSearch\Proxies\Converters\MessageConverterInterface $converter
     *   The converter to use for the conversion.
     * @param mixed                                                         $response
     *   The response received from the Transporters layer
     *
     * @return \OpenEuropa\EuropaSearch\Messages\MessageInterface
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
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig $configuration
     *   The web service client configuration for the initialization.
     */
    public function initProxy(EuropaSearchConfig $configuration);
}
