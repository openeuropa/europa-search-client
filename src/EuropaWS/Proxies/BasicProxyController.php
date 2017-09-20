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
use EC\EuropaWS\Exceptions\WebServiceErrorException;
use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Messages\MessageInterface;
use EC\EuropaWS\Messages\StringResponseMessage;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Transporters\TransporterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
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
class BasicProxyController implements ProxyControllerInterface, ContainerAwareInterface
{

    use ContainerAwareTrait;

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
    protected $WSConfiguration;

    /**
     * {@inheritdoc}
     */
    public function initProxy(WSConfigurationInterface $configuration)
    {
        $this->WSConfiguration = $configuration;
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

        $serviceList = $this->container->getServiceIds();

        $converterList = array_filter($serviceList, function ($value) {
            $isMessageId = (strpos($value, self::MESSAGE_ID_PREFIX) === 0);
            $isComponentId = (strpos($value, self::COMPONENT_ID_PREFIX) === 0);

            return ($isMessageId || $isComponentId);
        });

        return $converterList;
    }

    /**
     * {@inheritdoc}
     *
     * @internal Do not used, it is designed for unit tests only.
     */
    public function getConverterObject($convertId)
    {

        $converterObject = $this->container->get($convertId);

        return $converterObject;
    }

    /**
     * {@inheritdoc}
     */
    public function convertMessage(MessageConverterInterface $converter, ValidatableMessageInterface $message)
    {

        try {
            $convertedMessage = $converter->convertMessage($converter, $this->WSConfiguration);

            return $convertedMessage;
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process for the message failed!',
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertMessageWithComponents(MessageConverterInterface $converter, ValidatableMessageInterface $message, array $convertedComponent)
    {

        try {
            $convertedMessage = $converter->convertMessageWithComponents(
                $message,
                $convertedComponent,
                $this->WSConfiguration
            );

            return $convertedMessage;
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process of the message with its components failed!',
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
                $converterId = $component->getConverterIdentifier();
                $converter = $this->container->get($converterId);
                $convertedComponents[$key] = $this->convertComponent($converter, $component);
            }

            return $convertedComponents;
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process of the components failed!',
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertComponent(ComponentConverterInterface $converter, ComponentInterface $component)
    {

        try {
            $convertedComponent = $converter->convertComponent($component);

            return $convertedComponent;
        } catch (ServiceCircularReferenceException $scre) {
            throw new ClientInstantiationException(
                'The conversion of the component failed because of client implementation problem!',
                $scre
            );
        } catch (ServiceNotFoundException $snfe) {
            throw new ClientInstantiationException(
                'The converter for the component has not been found!',
                $snfe
            );
        } catch (Exception $e) {
            throw new ProxyException(
                'The conversion process of the component failed!',
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(MessageInterface $message, TransporterInterface $transporter)
    {

        try {
            $converterId = $message->getConverterIdentifier();
            $converter = $this->container->get($converterId);
            if ($message instanceof ValidatableMessageInterface) {
                $convertedComponents = $this->convertComponents($message->getComponents());
                $request = $this->convertMessageWithComponents($converter, $message, $convertedComponents);
            } else {
                $request = $this->convertMessage($converter, $message);
            }

            $response = $transporter->send($request);

            return $this->convertResponse($converter, $response);
        } catch (ProxyException $pe) {
            throw $pe;
        } catch (ClientInstantiationException $cie) {
            throw $cie;
        } catch (ConnectionException $ce) {
            throw $ce;
        } catch (WebServiceErrorException $wse) {
            throw $wse;
        } catch (\Exception $e) {
            throw new ProxyException(
                'A problem occurred during the request treatment.',
                $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function convertResponse(MessageConverterInterface $converter, $response)
    {
        return $converter->convertMessageResponse($response, $this->WSConfiguration);
    }
}
