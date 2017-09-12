<?php

/**
 * @file
 * Contains EC\EuropaWS\Transporters\TransporterInterface/
 */

namespace EC\EuropaWS\Transporters;

use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Exceptions\WebServiceErrorException;
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
     *   It is raised if the connection with the web service fails.
     * @throws WebServiceErrorException
     *   It is raised if the web service returns an error.
     * @throws ClientInstantiationException
     *   It is raised if the request object is not valid.
     */
    public function send(RequestInterface $request);

    /**
     * Initializes the web service connection configuration.
     *
     * @param WSConfigurationInterface $configuration
     *   The web service configuration to use in the initialization.
     */
    public function initTransporter(WSConfigurationInterface $configuration);
}
