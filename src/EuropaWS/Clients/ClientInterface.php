<?php

/**
 * @file
 * Contains EC\EuropaWS\Clients\ClientInterface.
 */

namespace EC\EuropaWS\Clients;

use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Exceptions\ProxyException;
use EC\EuropaWS\Exceptions\ValidationException;
use EC\EuropaWS\Messages\ValidatableMessageInterface;

/**
 * Interface ClientInterface.
 *
 * Implementing this interface allows objects to be the entry point to a
 * entry point to a web service client.
 *
 * All requests from a system pass by it to be submitted to the web service.
 *
 * @package EC\EuropaWS\Clients
 */
interface ClientInterface
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
}