<?php

namespace EC\EuropaSearch\Transporters;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Exceptions\ClientInstantiationException;
use EC\EuropaSearch\Exceptions\ConnectionException;
use EC\EuropaSearch\Exceptions\WebServiceErrorException;
use EC\EuropaSearch\Transporters\Requests\RequestInterface;

/**
 * Interface TransporterInterface.
 *
 * Implementing this interface allows object to send the request to the
 * web service.
 *
 * @package EC\EuropaSearch\Transporters
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
     * @param EuropaSearchConfig $configuration
     *   The web service configuration to use in the initialization.
     */
    public function initTransporter(EuropaSearchConfig $configuration);
}
