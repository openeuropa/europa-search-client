<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\ProxyProvider.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Exceptions\ProxyException;
use EC\EuropaWS\Exceptions\WebServiceErrorException;
use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Messages\MessageInterface;
use EC\EuropaWS\Messages\StringResponseMessage;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Messages\RequestInterface;
use EC\EuropaWS\Transporters\TransporterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class ProxyProvider.
 *
 * It supplies the right Proxy class for treating a message or component.
 *
 * @package EC\EuropaWS\Proxies
 */
class ProxyProvider
{
    /**
     * The proxy container to use in the different methods.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private static $container = null;

    /**
     * Prefix for identifier of service used as message proxy.
     */
    const MESSAGE_ID_PREFIX = 'messageProxy.';

    /**
     * Prefix for identifier of service used as component proxy.
     */
    const COMPONENT_ID_PREFIX = 'componentProxy.';

    /**
     * ProxyProvider constructor.
     */
    public function __construct()
    {

        if (is_null(static::$container)) {
            static::$container = new ContainerBuilder();
        }
    }

    /**
     * Adds a message converter to the object registry.
     *
     * @param string                    $converterId
     *   The id of the converter into the registry.
     * @param MessageConverterInterface $converter
     *   The message converter to add.
     */
    public function defineMessageConverter($converterId, MessageConverterInterface $converter)
    {

        static::$container->register($converterId, $converter);
    }

    /**
     * Adds a component converter to the object registry.
     *
     * @param string                      $converterId
     *   The id of the converter into the registry.
     * @param ComponentConverterInterface $converter
     *   The component converter to add.
     */
    public function defineComponentConverter($converterId, ComponentConverterInterface $converter)
    {

        static::$container->register($converterId, $converter);
    }

    /**
     * Gets the id list of all registered converter instances.
     *
     * @return array
     *   The id list of the registered converter instances.
     *
     * @internal Do not used, it is designed for unit tests only.
     */
    public function getConverterIdList()
    {
        $servicelist = static::$container->getServiceIds();

        $converterList = array_filter($servicelist, function ($value) {
            $isMessageId = (strpos($value, self::MESSAGE_ID_PREFIX) === 0);
            $isComponentId = (strpos($value, self::COMPONENT_ID_PREFIX) === 0);

            return ($isMessageId || $isComponentId);
        });

        return $converterList;
    }

    /**
     * Gets a specific converter instances.
     *
     * @param string $convertId
     *   The converter registry id.
     *
     * @return mixed
     *   The converter instance.
     *
     * @internal Do not used, it is designed for unit tests only.
     */
    public function getConverterObject($convertId)
    {
        $converterObject = static::$container->get($convertId);

        return $converterObject;
    }

    /**
     * Converts the message.
     *
     * @param ValidatableMessageInterface $message
     *   The message to convert.
     *
     * @return mixed
     *   The converted message.
     *
     * @throws ProxyException
     *   Raised if a problem occurred during the conversion process.
     */
    public function convertMessage(ValidatableMessageInterface $message)
    {

        try {
            $converterId = $message->getConverterIdentifier();
            $converter = static::$container->get($converterId);
            $convertedMessage = $converter->convertMessage($converterId);

            return $convertedMessage;
        } catch (Exception $e) {
            throw new ProxyException('The conversion process for the message failed!', $e);
        }
    }

    /**
     * Converts the message and integrate their converted components in it.
     *
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
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent)
    {

        try {
            $converterId = $message->getConverterIdentifier();
            $converter = static::$container->get($converterId);
            $convertedMessage = $converter->convertMessageWithComponents($converterId, $convertedComponent);

            return $convertedMessage;
        } catch (Exception $e) {
            throw new ProxyException('The conversion process of the message with its components failed!', $e);
        }
    }

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
    public function convertComponents(array $components)
    {

        try {
            $convertedComponents = array();
            if (empty($components)) {
                return $convertedComponents;
            }

            foreach ($components as $key => $component) {
                $convertedComponents[$key] = $this->convertComponent($component);
            }

            return $convertedComponents;
        } catch (Exception $e) {
            throw new ProxyException('The conversion process of the components failed!', $e);
        }
    }

    /**
     * Converts a component.
     *
     * @param ComponentInterface $component
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
    public function convertComponent(ComponentInterface $component)
    {

        try {
            $converterId = $component->getConverterIdentifier();
            $converter = static::$container->get($converterId);
            $convertedComponent = $converter->convertComponent($converterId);

            return $convertedComponent;
        } catch (ServiceCircularReferenceException $scre) {
            throw new ClientInstantiationException('The conversion of the component failed because of client implementation problem!', $scre);
        } catch (ServiceNotFoundException $snfe) {
            throw new ClientInstantiationException('The converter for the component has not been found!', $snfe);
        } catch (Exception $e) {
            throw new ProxyException('The conversion process of the component failed!', $e);
        }
    }

    /**
     * Sends the request to teh web service via the Transporter layer.
     *
     * @param RequestInterface     $request
     *   The request to send.
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
    public function sendRequest(RequestInterface $request, TransporterInterface $transporter)
    {

        try {
            $response = $transporter->send($request);

            // TODO Waiting the actual implementation, we return directly the dummy string.
            return new StringResponseMessage($response);
        } catch (\Exception $e) {
            throw new ConnectionException('A problem occurred with the connection to the web service.', $e);
        }
    }
}
