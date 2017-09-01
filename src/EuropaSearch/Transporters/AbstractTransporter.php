<?php

/**
 * @file
 * Contains EC\EuropaSearch\Transporters\AbstractTransporter.
 */

namespace EC\EuropaSearch\Transporters;

use EC\EuropaWS\Transporters\TransporterInterface;
use EC\EuropaWS\Common\WSConfigurationInterface;
use GuzzleHttp\Client;

/**
 * Class AbstractTransporter.
 *
 * Extending this class allows objects to share the configuration definition
 * logic common to all transporter classes.
 *
 * @package EC\EuropaSearch\Transporters
 */
abstract class AbstractTransporter implements TransporterInterface
{

    /**
     * HTTP client configuration.
     *
     * @var WSConfigurationInterface
     */
    protected $configuration;

    /**
     * Guzzle Client use to manage each request to the REST services.
     *
     * @var \GuzzleHttp\Client
     */
    protected $HTTPClient;

    /**
     * {@inheritdoc}
     */
    public function setWSConfiguration(WSConfigurationInterface $configuration)
    {

        $this->configuration = $configuration;

        $connectionConfig = $configuration->getConnectionConfig();
        $HTTPClientConfig = [
            'base_uri' => $connectionConfig['URLRoot'],
        ];
        $this->HTTPClient = new Client($HTTPClientConfig);
    }
}
