<?php

namespace EC\EuropaSearch;

use GuzzleHttp\Handler\MockHandler;

/**
 * Class EuropaSearchConfig.
 *
 * Contains to the Europa Search service settings.
 *
 * @package EC\EuropaSearch
 */
class EuropaSearchConfig
{

    /**
     * Web service configuration for services to call.
     *
     * @var array
     */
    private $connectionConfig;

    /**
     * Web service user name.
     *
     * @var string
     */
    private $userName;

    /**
     * Web service user password.
     *
     * @var string
     */
    private $userPassword;

    /**
     * Flag indicating if the client must use a mock or not.
     *
     * @var boolean
     */
    private $useMock;

    /**
     * Mock definition to use in tests.
     *
     * @var array
     */
    private $mock;

    /**
     * Supported API version of the contacted service.
     *
     * @var string
     */
    private $supportedServiceAPIVersion = "~2";

    /**
     * EuropaSearchConfig constructor.
     *
     * @param array $connectionConfig
     *   The configuration to connect to targeted ES services.
     *   The array must contains these keys for indexing services (Ingestion):
     *   - 'url_root': [mandatory] URL root (without the last slash) where the
     *     Europa Search REST services to use are host;
     *     ex.: https://search.ec.europa.eu.
     *   - 'api_key' : [mandatory] The API key to communicate with all
     *     indexing requests.
     *   - 'database': [mandatory] The database name to communicate with all
     *      indexing requests.
     *   - 'proxy': [optional] Array of Proxy settings to with indexing request.
     *     If not set, The Transporters layer must use the host proxy
     *     settings to send requests. That has the same effect as having the
     *     child parameter 'configuration_type' equals to default;
     *     - 'configuration_type': [optional] string the proxy type
     *       to use with application requests. The possible values are:
     *       - 'default': The Transporters layer must use the host proxy
     *         settings to send requests;
     *       - 'custom': The Transporters layer must use a dedicated proxy
     *         to send requests; Then the 'custom_address' is
     *         mandatory.
     *       - 'none': The Transporters layer must bypass the proxy to send requests;
     *     - 'user_name': string the proxy credentials username;
     *       It is only to be set if 'configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'user_password': string the proxy credentials
     *       password;
     *       It is only to be set if configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'custom_address': string the URL of the proxy to use;
     *       It is only MANDATORY if the 'configuration_type'
     *       parameter value is 'custom';
     *   The array must contains these keys for search services:
     *   - 'url_root': [mandatory] URL root (without the last slash) where the
     *     Europa Search REST services to use are host;
     *     ex.: https://search.ec.europa.eu.
     *   - 'api_key' : [mandatory] The API key to communicate with all search requests.
     *   - 'proxy': [optional] Array of Proxy settings to with search request.
     *     If not set, The Transporters layer must use the host proxy
     *     settings to send requests. That has the same effect as having the
     *     child parameter 'configuration_type' equals to default;
     *     - 'configuration_type': [optional] string the proxy type
     *       to use with application requests. The possible values are:
     *       - 'default': The Transporters layer must use the host proxy
     *         settings to send requests;
     *       - 'custom': The Transporters layer must use a dedicated proxy
     *         to send requests; Then the 'custom_address' is
     *         mandatory.
     *       - 'none': The Transporters layer must bypass the proxy to send requests;
     *     - 'user_name': string the proxy credentials username;
     *       It is only to be set if 'configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'user_password': string the proxy credentials
     *       password;
     *       It is only to be set if configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'custom_address': string the URL of the proxy to use;
     *       It is only MANDATORY if the 'configuration_type'
     *       parameter value is 'custom';
     */
    public function __construct(array $connectionConfig)
    {
        $this->connectionConfig = $connectionConfig;
        $this->useMock = false;
    }

    /**
     * Gets mock handler to use in the tests.
     *
     * @return array
     *   Array containing the mock handler to use.
     */
    public function getMockConfigurations()
    {
        $mock = new MockHandler($this->mock);

        return [$mock];
    }

    /**
     * Sets the list of Response objects used by the mock.
     *
     * @param array $mock
     *   The list of Response objects.
     */
    public function setMockConfigurations(array $mock)
    {
        $this->mock = $mock;
    }

    /**
     * Sets the connection configuration for the search services.
     *
     * @param array $connectionConfig
     *   The connection configuration.
     */
    public function setConnectionConfigurations(array $connectionConfig)
    {
        $this->connectionConfig = $connectionConfig;
    }

    /**
     * Gets the user name.
     *
     * @return string
     *   The user name.
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Sets the user name.
     *
     * @param string $userName
     *   The user name.
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Gets the user password.
     *
     * @return string
     *   The user password.
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * Sets the user password.
     *
     * @param string $userPassword
     *   The user password.
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
    }

    /**
     * Gets the connection configuration for the Indexing and Search services.
     *
     * @return array
     *   The configuration to connect to targeted ES services.
     *   The array contains these keys for indexing services (Ingestion):
     *   - 'url_root': [mandatory] URL root (without the last slash) where the
     *     Europa Search REST services to use are host;
     *     ex.: https://search.ec.europa.eu.
     *   - 'api_key' : [mandatory] The API key to communicate with all
     *     indexing requests.
     *   - 'database': [mandatory] The database name to communicate with all
     *      indexing requests.
     *   - 'proxy': [optional] Array of Proxy settings to with indexing request.
     *     If not set, The Transporters layer must use the host proxy
     *     settings to send requests. That has the same effect as having the
     *     child parameter 'configuration_type' equals to default;
     *     - 'configuration_type': [optional] string the proxy type
     *       to use with application requests. The possible values are:
     *       - 'default': The Transporters layer must use the host proxy
     *         settings to send requests;
     *       - 'custom': The Transporters layer must use a dedicated proxy
     *         to send requests; Then the 'custom_address' is
     *         mandatory.
     *       - 'none': The Transporters layer must bypass the proxy to send requests;
     *     - 'user_name': string the proxy credentials username;
     *       It is only to be set if 'configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'user_password': string the proxy credentials
     *       password;
     *       It is only to be set if configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'custom_address': string the URL of the proxy to use;
     *       It is only MANDATORY if the 'configuration_type'
     *       parameter value is 'custom';
     *   The array contains these keys for search services:
     *   - 'url_root': [mandatory] URL root (without the last slash) where the
     *     Europa Search REST services to use are host;
     *     ex.: https://search.ec.europa.eu.
     *   - 'api_key' : [mandatory] The API key to communicate with all search requests.
     *   - 'proxy': [optional] Array of Proxy settings to with search request.
     *     If not set, The Transporters layer must use the host proxy
     *     settings to send requests. That has the same effect as having the
     *     child parameter 'configuration_type' equals to default;
     *     - 'configuration_type': [optional] string the proxy type
     *       to use with application requests. The possible values are:
     *       - 'default': The Transporters layer must use the host proxy
     *         settings to send requests;
     *       - 'custom': The Transporters layer must use a dedicated proxy
     *         to send requests; Then the 'custom_address' is
     *         mandatory.
     *       - 'none': The Transporters layer must bypass the proxy to send requests;
     *     - 'user_name': string the proxy credentials username;
     *       It is only to be set if 'configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'user_password': string the proxy credentials
     *       password;
     *       It is only to be set if configuration_type'
     *       parameter value is 'custom' AND if the custom proxy requires
     *       it.
     *     - 'custom_address': string the URL of the proxy to use;
     *       It is only MANDATORY if the 'configuration_type'
     *       parameter value is 'custom';
     */
    public function getConnectionConfigurations()
    {
        return $this->connectionConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials()
    {
        return [
            'credentials_name' => $this->userName,
            'credentials_password' => $this->userPassword,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function useMock()
    {
        return $this->useMock;
    }

    /**
     * Sets the "useMock" variable.
     *
     * @param boolean $useMock
     *   The variable value.
     */
    public function setUseMock($useMock)
    {
        $this->useMock = $useMock;
    }

    /**
     * Gets the Service API version used with the client.
     *
     * @return string
     *   The API version.
     */
    public function getSupportedServiceAPIVersion()
    {
        return $this->supportedServiceAPIVersion;
    }
}
