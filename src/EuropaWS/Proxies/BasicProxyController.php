<?php

/**
 * @file
 * Contains EC\EuropaWS\Proxies\BasicProxyController.
 */

namespace EC\EuropaWS\Proxies;

use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Exceptions\ProxyException;
use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Messages\StringResponseMessage;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Messages\RequestInterface;
use EC\EuropaWS\Transporters\TransporterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class BasicProxyController.
 *
 * It supplies the right Proxy class for treating a message that has a basic
 * components structure (one level).
 *
 * @package EC\EuropaWS\Proxies
 */
class BasicProxyController implements ProxyControllerInterface
{
    /**
     * The proxy container to use in the different methods.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected static $container = null;

    /**
     * Prefix for identifier of service used as message proxy.
     */
    const MESSAGE_ID_PREFIX = 'messageProxy.';

    /**
     * Prefix for identifier of service used as component proxy.
     */
    const COMPONENT_ID_PREFIX = 'componentProxy.';

    /**
     * Web service configuration.
     *
     * @var WSConfigurationInterface
     */
    private $WSConfiguration;

    /**
     * BasicProxyController constructor.
     *
     * @param WSConfigurationInterface $WSConfiguration
     */
    public function __construct(WSConfigurationInterface $WSConfiguration)
    {

        $this->WSConfiguration = $WSConfiguration;

        if (is_null(static::$container)) {
            static::$container = new ContainerBuilder();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function defineMessageConverter($converterId, MessageConverterInterface $converter)
    {

        static::$container->register($converterId, $converter);
    }

    /**
     * {@inheritdoc}
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

        $serviceList = static::$container->getServiceIds();

        $converterList = array_filter($serviceList, function ($value) {
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
     * {@inheritdoc}
     */
    public function convertMessage(ValidatableMessageInterface $message)
    {

        try {
            $converterId = $message->getConverterIdentifier();
            $converter = static::$container->get($converterId);
            $convertedMessage = $converter->convertMessage($converter, $this->WSConfiguration);

            return $convertedMessage;
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process for the message failed!',
                283,
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent)
    {

        try {
            $converterId = $message->getConverterIdentifier();
            $converter = static::$container->get($converterId);
            $convertedMessage = $converter->convertMessageWithComponents(
                $message,
                $convertedComponent,
                $this->WSConfiguration
            );

            return $convertedMessage;
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process of the message with its components failed!',
                283,
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertComponents(array $components)
    {

        try {
            $convertedComponents = [];
            if (empty($components)) {
                return $convertedComponents;
            }

            foreach ($components as $key => $component) {
                $convertedComponents[$key] = $this->convertComponent($component);
            }

            return $convertedComponents;
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process of the components failed!',
                283,
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertComponent(ComponentInterface $component)
    {

        try {
            $converterId = $component->getConverterIdentifier();
            $converter = static::$container->get($converterId);
            $convertedComponent = $converter->convertComponent($component);

            return $convertedComponent;
        } catch (ServiceCircularReferenceException $scre) {
            throw new ClientInstantiationException(
                'The conversion of the component failed because of client implementation problem!',
                281,
                $scre
            );
        } catch (ServiceNotFoundException $snfe) {
            throw new ClientInstantiationException(
                'The converter for the component has not been found!',
                281,
                $snfe
            );
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process of the component failed!',
                283,
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request, TransporterInterface $transporter)
    {

        try {
            $response = $transporter->send($request);

            // TODO Waiting the actual implementation, we return directly the dummy string.
            return new StringResponseMessage($response);
        } catch (\Exception $e) {
            throw new ConnectionException(
                'A problem occurred with the connection to the web service.',
                289,
                $e
            );
        }
    }
}