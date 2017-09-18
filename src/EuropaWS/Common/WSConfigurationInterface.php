<?php

/**
 * @file
 * Contains EC\EuropaWS\Common\WSConfigurationInterface.
 */

namespace EC\EuropaWS\Common;

/**
 * Interface WSConfigurationInterface.
 *
 * Implementing this interface allows object to inject web service
 * configuration to into the client like web service address and/or credentials.
 *
 * Nothing prevent to add specific additional info in implementations.
 *
 * @package EC\EuropaWS\Common
 */
interface WSConfigurationInterface
{

    /**
     * Gets the configuration for the connection with the web service.
     *
     * @return array
     *   The configuration to use.
     */
    public function getConnectionConfig();

    /**
     * Gets the credentials necessary to connect to the web service.
     *
     * @return array
     *   The credentials to use.
     */
    public function getCredentials();

    /**
     * Determines if the client must use a mock.
     *
     * @return boolean
     *   true if the client must use the mock; otherwise false.
     */
    public function useMock();

    /**
     * Gets mock configurations; if the client must use a mock.
     *
     * @return array
     *   The mock configurations.
     */
    public function getMockConfigurations();
}
