<?php

/**
 * @file
 * Contains EC\EuropaSearch\Clients\IndexingClient.
 */

namespace EC\EuropaSearch\Clients;

use EC\EuropaSearch\Messages\Index\IndexingWebContent;
use EC\EuropaWS\Clients\DefaultClient;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Exceptions\ValidationException;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Exceptions\ProxyException;

/**
 * Class IndexingClient.
 *
 * Client implementation to manage the indexing requests.
 *
 * @package EC\EuropaSearch\Clients
 */
class IndexingClient extends DefaultClient
{
    /**
     * {@inheritDoc}
     */
    public function sendMessage(ValidatableMessageInterface $message)
    {
        if ($message instanceof IndexingWebContent) {
            return $this->sendWebContentMessage($message);
        }
    }

    /**
     * Sends a message for indexing a web content to the web service.
     *
     * @param IndexingWebContent $message
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
    public function sendWebContentMessage(IndexingWebContent $message)
    {

        $this->validateMessage($message);
        $convertedComponents = $this->proxy->convertComponents($message->getComponents());
        $request = $this->proxy->convertMessageWithComponents($message, $convertedComponents);

        // TODO doing the final implementation when the transporter layer is done.
        $this->transporter->setWSConfiguration($this->WSConfiguration);
        $response = $this->transporter->send($request, $this->WSConfiguration);

        return $response;
    }
}
