<?php

namespace OpenEuropa\EuropaSearch\Applications;

use OpenEuropa\EuropaSearch\EuropaSearchConfig;
use OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Interface ApplicationInterface.
 *
 * Implementing this interface allows objects to be the entry point of a web
 * service client for the 3rd party system like Drupal.
 *
 * All requests from a system pass by it to be submitted to the web service.
 *
 * @package OpenEuropa\EuropaSearch\Applications
 */
interface ApplicationInterface
{
    /**
     * Sends a message to the web service.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to send.
     *
     * @return \OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface $response
     *   The web service response.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ValidationException
     *   Raised if the message is invalid.
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ProxyException
     *   Raised if the message processing in the proxy layer failed.
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ConnectionException
     *   Raised if the connection with web service failed.
     */
    public function sendMessage(ValidatableMessageInterface $message);

    /**
     * Validates a message.
     *
     * This method can be called in the sendMessage() method.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to validate.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ValidationException
     *  Raised if the message is invalid. It returns all errors messages.
     */
    public function validateMessage(ValidatableMessageInterface $message);

  /**
   * Sets the application specific configuration of the HTTP client.
   *
   * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig $configuration
   *   The application specific configuration.
   */
    public function setApplicationConfiguration(EuropaSearchConfig $configuration);

    /**
     * Gets the application specific configuration of the HTTP client.
     *
     * @return \OpenEuropa\EuropaSearch\EuropaSearchConfig
     *   The application specific configuration.
     */
    public function getApplicationConfiguration();
}
