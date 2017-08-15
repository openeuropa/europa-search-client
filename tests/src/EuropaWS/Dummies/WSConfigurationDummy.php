<?php
/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\WSConfigurationDummy.
 */

namespace EC\EuropaWS\Tests\Dummies;

use EC\EuropaWS\Common\WSConfigurationInterface;

/**
 * Class WSConfigurationDummy.
 *
 * WSConfigurationInterface implementation for unit tests.
 *
 * @package EC\EuropaWS\Tests\Dummies
 */
class WSConfigurationDummy implements WSConfigurationInterface
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
     * WSConfigurationDummy constructor.
     *
     * @param array  $connectionConfig
     *   The connection configuration
     * @param string $userName
     *   The user name.
     * @param string $userPassword
     *   The user password.
     */
    public function __construct(array $connectionConfig, $userName, $userPassword)
    {
        $this->connectionConfig = $connectionConfig;
        $this->userName = $userName;
        $this->userPassword = $userPassword;
    }

    /**
     * Gets the connection configuration.
     *
     * @return array
     *   The connection configuration
     */
    public function getConnectionConfiguration()
    {
        return $this->connectionConfig;
    }

    /**
     * Sets the connection configuration.
     *
     * @param array $connectionConfig
     *   The connection configuration
     */
    public function setConnectionConfiguration(array $connectionConfig)
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
     * {@inheritDoc}
     */
    public function getConnectionConfig()
    {

        return array(
            'ws.connection.url' => $this->connectionConfig,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials()
    {

        return array(
            'ws.credentials.name' => $this->userName,
            'ws.credentials.password' => $this->userPassword,
        );
    }
}
