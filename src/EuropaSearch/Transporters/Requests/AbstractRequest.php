<?php

namespace OpenEuropa\EuropaSearch\Transporters\Requests;

/**
 * Class AbstractRequest.
 *
 * It represent a generic request content sent to the
 * Index Europa Search service.
 *
 * @package OpenEuropa\EuropaSearch\Transporters\Requests
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

    /**
     * Gets the HTTP method to use with the request.
     *
     * @return string
     *   The HTTP method to use; I.E. POST, GET, DELETE...
     */
    abstract public function getRequestMethod();

    /**
     * Gets the service URI to use with the request.
     *
     * @return string
     *   The service URI.
     */
    abstract public function getRequestURI();

    /**
     * Gets the options of the request to sent.
     *
     * @return array
     *   The request options.
     */
    abstract public function getRequestOptions();
}
