<?php

namespace EC\EuropaSearch\Transporters;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Exceptions\ConnectionException;
use EC\EuropaSearch\Exceptions\WebServiceErrorException;
use EC\EuropaSearch\Transporters\Requests\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

/**
 * Class EuropaSearchTransporter.
 *
 * n charge to send the requests to the REST services of
 * Europa Search (Ingestion API and Search API).
 *
 * @package EC\EuropaSearch\Transporters
 */
class EuropaSearchTransporter implements TransporterInterface
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
     * @var EuropaSearchConfig
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
    public function initTransporter(EuropaSearchConfig $configuration)
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

        $connectionConfig = $configuration->getConnectionConfigurations();
        $HTTPClientConfig = [
            'base_uri' => $connectionConfig['url_root'],
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

    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request)
    {
        $requestOptions = $request->getRequestOptions();
        $method = $request->getRequestMethod();
        $uri = $request->getRequestURI();

        try {
            return $this->HTTPClient->request($method, $uri, $requestOptions);
        } catch (ServerException $requestException) {
            throw new ConnectionException('The connection to the service fails', $requestException);
        } catch (ClientException $requestException) {
            throw new WebServiceErrorException('The request sent to the service returned an error', $requestException);
        }
    }
}
