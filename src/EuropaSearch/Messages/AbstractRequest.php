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
     * The API key to send with the request.
     *
     * @var string
     */
    private $APIKey;

    /**
     * Gets the API key to send.
     *
     * @return string
     *   The API key to send.
     */
    public function getAPIKey()
    {
        return $this->APIKey;
    }

    /**
     * Sets the API key to send.
     *
     * @param string $APIKey
     *   The API key to send.
     */
    public function setAPIKey($APIKey)
    {
        $this->APIKey = $APIKey;
    }
}