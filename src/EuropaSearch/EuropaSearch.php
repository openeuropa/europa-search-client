<?php

namespace EC\EuropaSearch;

use EC\EuropaSearch\Applications\Application;
use EC\EuropaSearch\Applications\ApplicationInterface;
use EC\EuropaSearch\Messages\DefaultValidatorBuilder;
use EC\EuropaSearch\Exceptions\ClientInstantiationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class EuropaSearch
 *
 * It is the client container factory of Europa Search.
 *
 * @package EC\EuropaSearch
 */
class EuropaSearch
{
    const CONFIGURATION_INDEXING_SERVICE_PARAM_NAME = 'indexing_settings';
    const CONFIGURATION_SEARCH_SERVICE_PARAM_NAME = 'search_settings';
    /**
     * Client container.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

    /**
     * Path of the repository path with services configuration file.
     *
     * @var string
     */
    protected $configRepoPath;

    /**
     * The client configuration.
     *
     * @var array
     */
    protected $clientConfiguration = [];

    /**
     * EuropaSearch constructor.
     *
     * @param array $clientConfiguration
     *   Array with client configuration. The client configuration is optional
     *   at the instantiation time but it must be set when the "Application"
     *   objects are used for sending messages.
     *   It contains the following keys:
     *   - 'indexing_settings': Array with the configuration for the
     *      Indexing application with the following keys:
     *      - 'url_root': [mandatory] URL root (without the last slash) where the
     *         Europa Search REST services to use are host;
     *         ex.: https://search.ec.europa.eu.
     *      - 'api_key' : [mandatory] The API key to communicate with all
     *         indexing requests.
     *      - 'database': [mandatory] The database name to communicate with all
     *        indexing requests.
     *   - 'search_settings': Array with the configuration for the
     *      search application with the following keys:
     *      - 'url_root': [mandatory] URL root (without the last slash) where the
     *        Europa Search REST services to use are host;
     *        ex.: https://search.ec.europa.eu.
     *      - 'api_key' : [mandatory] The API key to communicate with all search requests.
     */
    public function __construct(array $clientConfiguration = array())
    {
        $this->configRepoPath = __DIR__.'/config';

        if (!empty($clientConfiguration[self::CONFIGURATION_INDEXING_SERVICE_PARAM_NAME])) {
            $config = new EuropaSearchConfig($clientConfiguration[self::CONFIGURATION_SEARCH_SERVICE_PARAM_NAME]);
            $this->clientConfiguration[self::CONFIGURATION_SEARCH_SERVICE_PARAM_NAME] = $config;
        }

        if (!empty($clientConfiguration[self::CONFIGURATION_SEARCH_SERVICE_PARAM_NAME])) {
            $config = new EuropaSearchConfig($clientConfiguration[self::CONFIGURATION_SEARCH_SERVICE_PARAM_NAME]);
            $this->clientConfiguration[self::CONFIGURATION_SEARCH_SERVICE_PARAM_NAME] = $config;
        }
    }

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
     * @param string             $applicationId
     *   The application implementation id.
     * @param EuropaSearchConfig $configuration
     *   The configuration required by the called application.
     *
     * @return Application
     *   The client related application.
     *
     * @throws ClientInstantiationException
     *   Raised if a problem occurred while retrieving the client.
     */
    public function getApplication($applicationId, EuropaSearchConfig $configuration)
    {
        try {
            $application = $this->getClientContainer()->get($applicationId);
            $application->setApplicationConfiguration($configuration);

            return $application;
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The application instance is not retrieved.', $e);
        }
    }

    /**
     * Gets the indexing application.
     *
     * @return \EC\EuropaSearch\Applications\Application
     *   The indexing application.
     *
     * @throws ClientInstantiationException
     *   Raised if a problem occurred while retrieving the application.
     */
    public function getIndexingApplication()
    {
        $applicationConfig = $this->clientConfiguration[self::CONFIGURATION_INDEXING_SERVICE_PARAM_NAME];

        return $this->getApplication('application.default', $applicationConfig);
    }

    /**
     * Gets the search application.
     *
     * @return \EC\EuropaSearch\Applications\Application
     *   The search application.
     *
     * @throws ClientInstantiationException
     *   Raised if a problem occurred while retrieving the application.
     */
    public function getSearchApplication()
    {
        $applicationConfig = $this->clientConfiguration[self::CONFIGURATION_SEARCH_SERVICE_PARAM_NAME];

        return $this->getApplication('application.default', $applicationConfig);
    }

    /**
     * Updates the search application configuration.
     *
     * @param EuropaSearchConfig        $searchConfig
     *    Object with the new configuration definition.
     * @param ApplicationInterface|null $searchApplication
     *    [optional] The search application already instantiated and must take
     *    into account of the new configuration.
     *
     * {@internal Designed primarily for unit test purpose.}
     */
    public function updateSearchClientConfiguration(EuropaSearchConfig $searchConfig, ApplicationInterface $searchApplication = null)
    {
        $this->clientConfiguration[self::CONFIGURATION_SEARCH_SERVICE_PARAM_NAME] = $searchConfig;
        if (!empty($searchApplication)) {
            $searchApplication->setApplicationConfiguration($searchConfig);
        }
    }

    /**
     * Updates the indexing application configuration.
     *
     * @param EuropaSearchConfig        $indexingConfig
     *    Object with the new configuration definition.
     * @param ApplicationInterface|null $indexingApplication
     *    [Optional] The indexing application already instantiated and must
     *    take into account of the new configuration.
     *
     * {@internal Designed primarily for unit test purpose.}
     */
    public function updateIndexingClientConfiguration(EuropaSearchConfig $indexingConfig, ApplicationInterface $indexingApplication = null)
    {
        $this->clientConfiguration[self::CONFIGURATION_INDEXING_SERVICE_PARAM_NAME] = $indexingConfig;
        if (!empty($indexingApplication)) {
            $indexingApplication->setApplicationConfiguration($indexingConfig);
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
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The client container instantiation failed.', $e);
        }
    }
}
