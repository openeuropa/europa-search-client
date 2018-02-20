<?php

namespace OpenEuropa\EuropaSearch\Transporters;

use OpenEuropa\EuropaSearch\EuropaSearchConfig;
use OpenEuropa\EuropaSearch\Transporters\Requests\RequestInterface;

/**
 * Interface TransporterInterface.
 *
 * Implementing this interface allows object to send the request to the
 * web service.
 *
 * @package OpenEuropa\EuropaSearch\Transporters
 */
interface TransporterInterface
{
    /**
     * Sends a request to the web service.
     *
     * @param \OpenEuropa\EuropaSearch\Transporters\Requests\RequestInterface $request
     *   The request to send.
     *
     * @return mixed
     *   The response from the web service.
     *
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ConnectionException
     *   It is raised if the connection with the web service fails.
     * @throws \OpenEuropa\EuropaSearch\Exceptions\WebServiceErrorException
     *   It is raised if the web service returns an error.
     * @throws \OpenEuropa\EuropaSearch\Exceptions\ClientInstantiationException
     *   It is raised if the request object is not valid.
     */
    public function send(RequestInterface $request);

    /**
     * Initializes the web service connection configuration.
     *
     * @param \OpenEuropa\EuropaSearch\EuropaSearchConfig $configuration
     *   The web service configuration to use in the initialization.
     */
    public function initTransporter(EuropaSearchConfig $configuration);
}
