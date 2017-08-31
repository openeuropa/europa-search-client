<?php

/**
 * @file
 * Contains EC\EuropaSearch\Transporters\IndexingTransporter.
 */

namespace EC\EuropaSearch\Transporters;

use EC\EuropaSearch\Messages\Index\WebContentRequest;
use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Messages\RequestInterface;
use EC\EuropaWS\Transporters\TransporterInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class IndexingTransporter.
 *
 * In charge to send the requests to the Indexing REST services of
 * Europa Search (Ingestion API).
 *
 * @package EC\EuropaSearch\Transporters
 */
class IndexingTransporter implements TransporterInterface
{

    /**
     * HTTP client configuration.
     *
     * @var WSConfigurationInterface
     */
    private $configuration;
    /**
     * Guzzle Client use to manage each request to the REST services.
     *
     * @var \GuzzleHttp\Client
     */
    private $HTTPClient;

    /**
     * {@inheritdoc}
     */
    public function send(RequestInterface $request)
    {
        if ($request instanceof WebContentRequest) {
            return $this->sendWebContentRequest($request);
        }

        throw new ClientInstantiationException('The transporter layer did not receive the right object type. It receives: '.get_class($request));
    }

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

    /**
     * Send a indexing request for a web content to the Europa Search REST service.
     *
     * @param WebContentRequest $request
     *   The indexing request for the web content.
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *   The raw service response.
     *
     * @throws ConnectionException
     *   If the connection with the services failed.
     */
    private function sendWebContentRequest(WebContentRequest $request)
    {

        try {
            $requestOptions = [
                'multipart' => $request->getRequestBody(),
                'query' => $request->getRequestQuery(),
            ];

            return $this->HTTPClient->request('POST', '/es/ingestion-api/rest/ingestion/text', $requestOptions);
        } catch (RequestException $requestException) {
            throw new ConnectionException('The connection to the Ingestion service fails', 289, $requestException);
        }
    }
}
