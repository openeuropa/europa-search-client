<?php

namespace EC\EuropaSearch\Applications;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Interface ApplicationInterface.
 *
 * Implementing this interface allows objects to be the entry point of a web
 * service client for the 3rd party system like Drupal.
 *
 * All requests from a system pass by it to be submitted to the web service.
 *
 * @package EC\EuropaSearch\Applications
 */
interface ApplicationInterface
{

    /**
     * Sends a message to the web service.
     *
     * @param \EC\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to send.
     *
     * @return \EC\EuropaSearch\Messages\ValidatableMessageInterface $response
     *   The web service response.
     *
     * @throws \EC\EuropaSearch\Exceptions\ValidationException
     *   Raised if the message is invalid.
     * @throws \EC\EuropaSearch\Exceptions\ProxyException
     *   Raised if the message processing in the proxy layer failed.
     * @throws \EC\EuropaSearch\Exceptions\ConnectionException
     *   Raised if the connection with web service failed.
     */
    public function sendMessage(ValidatableMessageInterface $message);

    /**
     * Validates a message.
     *
     * This method can be called in the sendMessage() method.
     *
     * @param \EC\EuropaSearch\Messages\ValidatableMessageInterface $message
     *   The message to validate.
     *
     * @throws \EC\EuropaSearch\Exceptions\ValidationException
     *  Raised if the message is invalid. It returns all errors messages.
     */
    public function validateMessage(ValidatableMessageInterface $message);

  /**
   * Sets the application specific configuration of the HTTP client.
   *
   * @param \EC\EuropaSearch\EuropaSearchConfig $configuration
   *   The application specific configuration.
   */
    public function setApplicationConfiguration(EuropaSearchConfig $configuration);

    /**
     * Gets the application specific configuration of the HTTP client.
     *
     * @return \EC\EuropaSearch\EuropaSearchConfig
     *   The application specific configuration.
     */
    public function getApplicationConfiguration();
}
