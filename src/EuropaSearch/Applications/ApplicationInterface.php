<?php

namespace EC\EuropaSearch\Applications;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Exceptions\ConnectionException;
use EC\EuropaSearch\Exceptions\ProxyException;
use EC\EuropaSearch\Exceptions\ValidationException;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Interface ApplicationInterface.
 *
 * Implementing this interface allows objects to be the entry point to a
 * entry point to a web service client.
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
     * @param ValidatableMessageInterface $message
     *   The message to send.
     *
     * @return ValidatableMessageInterface $response
     *   The web service response.
     *
     * @throws ValidationException
     *   Raised if the message is invalid.
     * @throws ProxyException
     *   Raised if the message processing in the proxy layer failed.
     * @throws ConnectionException
     *   Raised if the connection with web service failed.
     */
    public function sendMessage(ValidatableMessageInterface $message);

    /**
     * Validates a message.
     *
     * This method can be called in the sendMessage() method.
     *
     * @param ValidatableMessageInterface $message
     *   The message to validate.
     *
     * @throws ValidationException
     *  Raised if the message is invalid. It returns all errors messages.
     */
    public function validateMessage(ValidatableMessageInterface $message);

  /**
   * Sets the application specific configuration of the HTTP client.
   *
   * @param EuropaSearchConfig $configuration
   *   The application specific configuration.
   */
    public function setApplicationConfiguration(EuropaSearchConfig $configuration);

    /**
     * Gets the application specific configuration of the HTTP client.
     *
     * @return  EuropaSearchConfig
     *   The application specific configuration.
     */
    public function getApplicationConfiguration();
}
