<?php

namespace EC\EuropaSearch\Proxies;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Messages\Components\NestedComponentInterface;
use EC\EuropaSearch\Exceptions\ClientInstantiationException;
use EC\EuropaSearch\Exceptions\ConnectionException;
use EC\EuropaSearch\Exceptions\ProxyException;
use EC\EuropaSearch\Exceptions\WebServiceErrorException;
use EC\EuropaSearch\Messages\Components\ComponentInterface;
use EC\EuropaSearch\Messages\MessageInterface;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;
use EC\EuropaSearch\Proxies\Converters\Components\Filters\Queries\FilterQueryConverterInterface;
use EC\EuropaSearch\Proxies\Converters\Components\ComponentConverterInterface;
use EC\EuropaSearch\Proxies\Converters\MessageConverterInterface;
use EC\EuropaSearch\Transporters\TransporterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class ProxyController.
 *
 * ProxyController implementation to manage the message dispatching.
 *
 * @package EC\EuropaSearch\Proxies
 */
class ProxyController implements ProxyControllerInterface, ContainerAwareInterface
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
     * @var EuropaSearchConfig
     */
    protected $WSConfiguration;

    /**
     * {@inheritdoc}
     */
    public function initProxy(EuropaSearchConfig $configuration)
    {
        $this->WSConfiguration = $configuration;
    }

    /**
     * {@inheritdoc}
     *
     * {@internal Do not used, it is designed for unit tests only.}
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
            $convertedMessage = $converter->convertMessage($message, $this->WSConfiguration);
        } catch (Exception $e) {
            throw new ProxyException('The conversion process for the message failed!', $e);
        }

        return $convertedMessage;
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
        } catch (Exception $e) {
            throw new ProxyException('The conversion process of the message with its components failed!', $e);
        }

        return $convertedMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function convertComponents(array $components)
    {
        $convertedComponents = [];

        try {
            foreach ($components as $key => $component) {
                $converterId = $component->getConverterIdentifier();
                $converter = $this->container->get($converterId);
                $convertedComponents[$key] = $this->convertComponent($converter, $component);
            }
        } catch (Exception $e) {
            throw new ProxyException('The conversion process of the components failed!', $e);
        }

        return $convertedComponents;
    }

    /**
     * {@inheritDoc}
     */
    public function convertComponent(ComponentConverterInterface $converter, ComponentInterface $component)
    {
        if ($component instanceof NestedComponentInterface) {
            $converterId = $component->getConverterIdentifier();
            $converter = $this->container->get($converterId);

            return $this->convertNestedComponentWithChildren($converter, $component);
        }

        try {
            $convertedComponent = $converter->convertComponent($component);
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

        return $convertedComponent;
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
            throw new ProxyException('A problem occurred during the request treatment.', $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function convertResponse(MessageConverterInterface $converter, $response)
    {
        return $converter->convertMessageResponse($response, $this->WSConfiguration);
    }

    /**
     * Converts a NestedComponentInterface component with its child components.
     *
     * @param FilterQueryConverterInterface $converter
     *   The converter to use.
     * @param NestedComponentInterface      $nestedComponent
     *   The component to convert.
     *
     * @return mixed
     *   The converted component.
     *
     * @throws ClientInstantiationException
     *   Raised if the process failed because of the client instantiation problem.
     * @throws ProxyException
     *   Raised if a problem occurred during the conversion process.
     */
    public function convertNestedComponentWithChildren(FilterQueryConverterInterface $converter, NestedComponentInterface $nestedComponent)
    {
        try {
            $children = $nestedComponent->getChildComponents();

            $convertedComponents = [];
            if (empty($convertedComponents) && !empty($children)) {
                $convertedComponents = $this->convertComponents($children);
            }
            $convertedComponent = $converter->convertComponentWithChildren($nestedComponent, $convertedComponents);

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
        } catch (\Exception $e) {
            throw new ProxyException(
                'The conversion process of the component failed!',
                $e
            );
        }
    }
}
