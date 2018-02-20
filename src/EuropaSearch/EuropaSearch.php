<?php

namespace OpenEuropa\EuropaSearch;

use OpenEuropa\EuropaSearch\Applications\ApplicationInterface;
use OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class EuropaSearch
 *
 * It is the client container factory of Europa Search.
 *
 * @package OpenEuropa\EuropaSearch
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
     *      - 'url_root': [mandatory] string URL root (without the last slash) where the
     *         Europa Search REST services to use are host;
     *         ex.: https://search.ec.europa.eu.
     *      - 'api_key' : [mandatory] string the API key to communicate with all
     *         indexing requests.
     *      - 'database': [mandatory] string the database name to communicate
     *         with all indexing requests.
     *      - 'proxy': [optional] Array of Proxy settings to with indexing request.
     *         - 'proxy_configuration_type': [optional] string the proxy type
     *           to use with application requests. The possible values are:
     *           'default': The Transporters layer must use the host proxy
     *            settings to send requests;
     *           'custom': The Transporters layer must use a dedicated proxy
     *            to send requests; Then the 'custom_proxy_address' is
     *            mandatory.
     *           'none': The Transporters layer must bypass the proxy to send requests;
     *         - 'user_name': string the proxy credentials username;
     *           It is only to be set if 'proxy_configuration_type'
     *           parameter value is 'custom' AND if the custom proxy requires
     *           it.
     *         - 'user_password': string the proxy credentials
     *           password;
     *           It is only to be set if 'proxy_configuration_type'
     *           parameter value is 'custom' AND if the custom proxy requires
     *           it.
     *         - 'custom_proxy_address': string the URL of the proxy to use;
     *           It is only MANDATORY if the 'proxy_configuration_type'
     *           parameter value is 'custom';
     *   - 'search_settings': Array with the configuration for the
     *      search application with the following keys:
     *      - 'url_root': [mandatory] URL root (without the last slash) where the
     *        Europa Search REST services to use are host;
     *        ex.: https://search.ec.europa.eu.
     *      - 'api_key' : [mandatory] The API key to communicate with all
     *         search requests.
     *      - 'proxy': [optional] Array of Proxy settings to with indexing request.
     *        If not set, The Transporters layer must use the host proxy
     *        settings to send requests. That has the same effect as having the
     *        child parameter 'configuration_type' equals to default;
     *         - 'configuration_type': [optional] string the proxy type
     *           to use with application requests. The possible values are:
     *           'default': The Transporters layer must use the host proxy
     *            settings to send requests;
     *           'custom': The Transporters layer must use a dedicated proxy
     *            to send requests; Then the 'custom_address' is
     *            mandatory.
     *           'none': The Transporters layer must bypass the proxy to send requests;
     *         - 'user_name': string the proxy credentials username;
     *           It is only to be set if 'configuration_type'
     *           parameter value is 'custom' AND if the custom proxy requires
     *           it.
     *         - 'user_password': string the proxy credentials
     *           password;
     *           It is only to be set if configuration_type'
     *           parameter value is 'custom' AND if the custom proxy requires
     *           it.
     *         - 'custom_address': string the URL of the proxy to use;
     *           It is only MANDATORY if the 'configuration_type'
     *           parameter value is 'custom';
     *   -  'services_settings': Settings specific related to the client itself.
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
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     *   The client container.
     */
    public function getClientContainer()
    {
        return $this->container;
    }

    /**
     * Gets the default library validator.
     *
     * @return \Symfony\Component\Validator\Validator\RecursiveValidator
     *   The default validator object.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException
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
     * @param string                              $applicationId
     *   The application implementation id.
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig $configuration
     *   The configuration required by the called application.
     *
     * @return \OpenEuropa\EuropaSearch\Applications\Application
     *   The client related application.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException
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
     * @return \OpenEuropa\EuropaSearch\Applications\Application
     *   The indexing application.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException
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
     * @return \OpenEuropa\EuropaSearch\Applications\Application
     *   The search application.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException
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
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig                     $searchConfig
     *    Object with the new configuration definition.
     * @param \OpenEuropa\EuropaSearch\Applications\ApplicationInterface|null $searchApplication
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
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig                     $indexingConfig
     *    Object with the new configuration definition.
     * @param \OpenEuropa\EuropaSearch\Applications\ApplicationInterface|null $indexingApplication
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
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException
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
