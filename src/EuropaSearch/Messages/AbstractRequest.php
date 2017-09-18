<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\AbstractRequest.
 */

namespace EC\EuropaSearch\Messages;

use EC\EuropaWS\Messages\RequestInterface;

/**
 * Class AbstractRequest.
 *
 * It represent a generic request content sent to the
 * Index Europa Search service.
 *
 * @package EC\EuropaSearch\Messages
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * HTTP request body.
     *
     * @var array
     */
    protected $body = [];

    /**
     * HTTP request query.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Gets the API key to send.
     *
     * @return string
     *   The API key to send.
     */
    public function getAPIKey()
    {
        return $this->query['apiKey'];
    }

    /**
     * Sets the API key to send.
     *
     * @param string $APIKey
     *   The API key to send.
     */
    public function setAPIKey($APIKey)
    {
        $this->query['apiKey'] = $APIKey;
    }

    /**
     * Gets the Request body sent to the client.
     *
     * @return array
     *   The body structure.
     */
    public function getRequestBody()
    {
        return array_values($this->body);
    }

    /**
     * Gets the Request query parameters sent to the client.
     *
     * @return array
     *   The query parameters.
     */
    public function getRequestQuery()
    {
        return $this->query;
    }
}
