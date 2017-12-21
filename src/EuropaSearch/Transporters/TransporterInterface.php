<?php

namespace EC\EuropaSearch\Transporters;

use EC\EuropaSearch\EuropaSearchConfig;
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
     * @param \EC\EuropaSearch\Transporters\Requests\RequestInterface $request
     *   The request to send.
     *
     * @return mixed
     *   The response from the web service.
     *
     * @throws \EC\EuropaSearch\Exceptions\ConnectionException
     *   It is raised if the connection with the web service fails.
     * @throws \EC\EuropaSearch\Exceptions\WebServiceErrorException
     *   It is raised if the web service returns an error.
     * @throws \EC\EuropaSearch\Exceptions\ClientInstantiationException
     *   It is raised if the request object is not valid.
     */
    public function send(RequestInterface $request);

    /**
     * Initializes the web service connection configuration.
     *
     * @param \EC\EuropaSearch\EuropaSearchConfig $configuration
     *   The web service configuration to use in the initialization.
     */
    public function initTransporter(EuropaSearchConfig $configuration);
}
