<?php

/**
 * @file
 * Contains EC\EuropaWS\Transporters\TransporterInterface/
 */

namespace EC\EuropaWS\Transporters;

use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Messages\RequestInterface;

/**
 * Interface TransporterInterface.
 *
 * Implementing this interface allows object to send the request to the
 * web service.
 *
 * @package EC\EuropaWS\Transporters
 */
interface TransporterInterface
{

    /**
     * Sends a request to the web service.
     *
     * @param RequestInterface $request
     *   The request to send.
     *
     * @return mixed
     *   The response from the web service.
     *
     * @throws ConnectionException
     *   It is raised if the
     */
    public function send(RequestInterface $request);

    /**
     * Sets the web service connection configuration.
     *
     * @param WSConfigurationInterface $configuration
     *   The web service configuration.
     */
    public function setWSConfiguration(WSConfigurationInterface $configuration);
}