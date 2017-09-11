<?php

/**
 * @file
 * Contains EC\EuropaWS\ClientContainerFactory.
 */

namespace EC\EuropaWS;

use EC\EuropaWS\Clients\ClientInterface;
use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Common\DefaultValidatorBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class ClientContainerFactory.
 *
 * It instaantiates the client container.
 *
 * Extending this class allows objects to load their own YML configuration file.
 *
 * @package EC\EuropaWS
 */
class ClientContainerFactory
{

    /**
     * Client container.
     *
     * @var \EC\EuropaWS\ClientContainer
     */
    protected $container;

    /**
     * Path of the repository path whith services configuration file.
     *
     * @var string
     */
    protected $configRepoPath;

    /**
     * The client configuration.
     *
     * @var WSConfigurationInterface
     */
    protected $configuration;

    /**
     * Gest the client container with the services definitions.
     *
     * @return ContainerBuilder
     *   The client container.
     */
    public function getClientContainer()
    {

        $this->buildClientContainer();

        return $this->container;
    }

    /**
     * ClientContainer constructor.
     *
     * @param WSConfigurationInterface $configuration
     *   The client configuration.
     */
    public function __construct(WSConfigurationInterface $configuration)
    {

        $this->configRepoPath = __DIR__.'/config';
        $this->configuration = $configuration;
    }

    /**
     * Gets the default library validator.
     *
     * @return RecursiveValidator
     *   The default validator object.
     *
     * @throws ClientInstantiationException
     *   Raised if a problem occurred while retrieving the client.
     */
    public function getDefaultValidator()
    {

        try {
            $validatorBuilder = $this->getClientContainer()->get('validator.default');

            return $validatorBuilder->getValidator();
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The client is not retrieved.', $e);
        }
    }

    /**
     * Gets a ClientInterface implementation from its id.
     *
     * @param string $clientId
     *   The client implementation id.
     *
     * @return ClientInterface $client
     *   The client implementation
     *
     * @throws ClientInstantiationException
     *   Raised if a problem occurred while retrieving the client.
     */
    public function getClient($clientId)
    {

        try {
            $client = $this->getClientContainer()->get($clientId);
            $client->setWSConfiguration($this->configuration);

            return $client;
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The client is not retrieved.', $e);
        }
    }

    /**
     * Builds the client container.
     *
     * @throws ClientInstantiationException
     *   It is catch when the YML file are not found or malformed.
     */
    protected function buildClientContainer()
    {

        if (!is_null($this->container)) {
            return;
        }

        $container = new ContainerBuilder();
        try {
            //Add the default validator implementation
            $container->register('validator.default', DefaultValidatorBuilder::class);
            $loader = new YamlFileLoader($container, new FileLocator($this->configRepoPath));
            $loader->load('services.yml');

            $this->container = $container;
        } catch (Exception $e) {
            throw new ClientInstantiationException('The client container instantiation failed.', $e);
        }
    }
}
