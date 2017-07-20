<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\IndexClient.
 */

namespace EC\EuropaSearch\Index\Client;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Index\IndexServiceContainer;

/**
 * Class IndexClient
 *
 * It manages client index requests for the Europa search services.
 *
 * @package EC\EuropaSearch\Index
 */
class IndexClient
{
    private $container;

    /**
     * IndexClient constructor.
     *
     * @param ServiceConfiguration $serviceConfiguration
     *    The client configuration with the service connection parameters.
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->container = new IndexServiceContainer($serviceConfiguration);
        $validationError = $this->container->get('validator')->validate($serviceConfiguration);
        if (count($validationError) > 0) {
            // TODO Continue the code. just here to show the next step.
        }
    }
}
