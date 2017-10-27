<?php

namespace EC\EuropaSearch;

use EC\EuropaSearch\Applications\Application;
use EC\EuropaSearch\Applications\ApplicationInterface;
use EC\EuropaSearch\Exceptions\ClientInstantiationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
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
    const CONFIGURATION_CLIENT_SERVICE_PARAM_NAME = 'services_settings';
    /**
     * Client container.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

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
     *      - 'api_key' : [mandatory] The API key to communicate with all
     *         search requests.
     *   -  'services_settings': Settings specific related tothe client itself.
     *        'logger': The PSR3 logger used to record logs from the client.
     *        'log_level': The PSR3 level of the logs to record with the PSR3 logger.
     *        See Psr\Log\LogLevel
     */
    public function __construct(array $clientConfiguration = array())
    {
        $clientConfiguration += array(self::CONFIGURATION_CLIENT_SERVICE_PARAM_NAME => array());

        $this->buildClientContainer($clientConfiguration[self::CONFIGURATION_CLIENT_SERVICE_PARAM_NAME]);

        if (!empty($clientConfiguration[self::CONFIGURATION_INDEXING_SERVICE_PARAM_NAME])) {
            $config = new EuropaSearchConfig($clientConfiguration[self::CONFIGURATION_INDEXING_SERVICE_PARAM_NAME]);
            $this->clientConfiguration[self::CONFIGURATION_INDEXING_SERVICE_PARAM_NAME] = $config;
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
            $validatorBuilder = $this->getClientContainer()->get('europaSearch.validator.default');

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

        return $this->getApplication('europaSearch.application.default', $applicationConfig);
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

        return $this->getApplication('europaSearch.application.default', $applicationConfig);
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
     * Gets the service defined in the client container by its id.
     *
     * @param string $serviceId
     *   The id of the service to retrieve.
     *
     * @return object
     *   The service instance stored in the container.
     *
     * {@internal Designed primarily for unit test purpose.}
     */
    public function getServiceById($serviceId)
    {
        return $this->container->get($serviceId);
    }

    /**
     * Builds the client container.
     *
     * @param array $servicesSettings
     *   Custom settings for the services container.
     *
     * @throws ClientInstantiationException
     *   It is catch when the YML file are not found or malformed.
     */
    protected function buildClientContainer(array $servicesSettings)
    {
        if (!is_null($this->container)) {
            return;
        }

        $container = new ContainerBuilder();
        try {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config'));
            $loader->load('services.yml');

            if (!empty($servicesSettings['logger'])) {
                $container->set('europaSearch.logger', $servicesSettings['logger']);
            }

            $this->container = $container;
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The client container instantiation failed.', $e);
        }
    }
}
