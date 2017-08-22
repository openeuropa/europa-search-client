<?php
/**
 * @file
 * Contains EC\EuropaSearch\EuropaSearchConfig.
 */

namespace EC\EuropaSearch;

use EC\EuropaWS\Common\WSConfigurationInterface;

/**
 * Class EuropaSearchConfig.
 *
 * WSConfigurationInterface implementation specific to the Europa Search service.
 *
 * @package EC\EuropaSearch
 */
class EuropaSearchConfig implements WSConfigurationInterface
{
    /**
     * Web service configuration.
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
     * EuropaSearchConfig constructor.
     *
     * @param array $connectionConfig
     *   The connection configuration
     */
    public function __construct(array $connectionConfig)
    {
        $this->connectionConfig = $connectionConfig;
    }


    /**
     * Gets the connection configuration.
     *
     * @return array
     *   The connection configuration
     */
    public function getConnectionConfigurations()
    {
        return $this->connectionConfig;
    }

    /**
     * Sets the connection configuration.
     *
     * @param array $connectionConfig
     *   The connection configuration
     */
    public function setConnectionConfigurations(array $connectionConfig)
    {
        $this->connectionConfig = $connectionConfig;
    }

    /**
     * Adds a specific settings parameter to the connection configuration.
     *
     * @param string $configKey
     *   The setting parameter name.
     * @param mixed  $configValue
     *   The setting parameter value; it can be a scalar or a array.
     */
    public function addConnectionConfigurations($configKey, $configValue)
    {
        $this->connectionConfig[$configKey] = $configValue;
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
     * {@inheritDoc}
     */
    public function getConnectionConfig()
    {
        return $this->connectionConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials()
    {

        return [
            'ws.credentials.name' => $this->userName,
            'ws.credentials.password' => $this->userPassword,
        ];
    }
}
