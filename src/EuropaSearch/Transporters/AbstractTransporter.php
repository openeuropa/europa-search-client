<?php

/**
 * @file
 * Contains EC\EuropaSearch\Transporters\AbstractTransporter.
 */

namespace EC\EuropaSearch\Transporters;

use EC\EuropaWS\Transporters\TransporterInterface;
use EC\EuropaWS\Common\WSConfigurationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

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
     * History of all requests sent to web services.
     *
     * @var array
     */
    protected $transactionHistory;

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
    public function initTransporter(WSConfigurationInterface $configuration)
    {

        $this->configuration = $configuration;
        $this->transactionHistory = [];
        $stack = HandlerStack::create();

        if ($configuration->useMock()) {
            // see EuropaSearchConfig definition.
            $mock = $configuration->getMockConfigurations();
            $mockHandler = reset($mock);
            $stack = HandlerStack::create($mockHandler);
        }

        $history = Middleware::history($this->transactionHistory);
        $stack->push($history);

        $connectionConfig = $configuration->getConnectionConfig();
        $HTTPClientConfig = [
            'base_uri' => $connectionConfig['URLRoot'],
            'handler' => $stack,
        ];
        $this->HTTPClient = new Client($HTTPClientConfig);
    }

    /**
     * gets the hostory of the requests sent to web services.
     *
     * @return array
     */
    public function getTransactionHistory()
    {
        return $this->transactionHistory;
    }
}
