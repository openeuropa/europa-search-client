<?php

namespace EC\EuropaSearch\Transporters;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Exceptions\ConnectionException;
use EC\EuropaSearch\Exceptions\WebServiceErrorException;
use EC\EuropaSearch\Services\LogsManager;
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
     * The default uri ste for the transporter.
     *
     * @var string
     */
    protected $uri;

    /**
     * The logs manager that will manage logs record.
     *
     * @var LogsManager
     */
    protected $logsManager;

    /**
     * EuropaSearchTransporter constructor.
     *
     * @param LogsManager $logsManager
     *   The logs manager that will manage logs record.
     */
    public function __construct(LogsManager $logsManager)
    {
        $this->logsManager = $logsManager;
    }

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

        $urlItems = $this->parseUrl($connectionConfig['url_root']);

        $this->uri = $urlItems['path'];

        $HTTPClientConfig = [
            'base_uri' => $urlItems['base_uri'],
            'handler' => $stack,
        ];
        $this->HTTPClient = new Client($HTTPClientConfig);

        if ($this->logsManager->isInfo()) {
            $this->logsManager->logInfo('Transporter is initialized.', array('transporter_settings' => $HTTPClientConfig));
        }
    }

  /**
   * Parse an URL.
   *
   * @param string $url
   *   The URL to parse.
   *
   * @return array
   *   Array containing:
   *   - 'base_uri': The url root.
   *   - 'path': the url path.
   */
    public function parseUrl($url)
    {
        $urlComponents = parse_url($url);

        $baseUri = $urlComponents['scheme'].'://'.$urlComponents['host'];
        if (!empty($urlComponents['port'])) {
            $baseUri .= $urlComponents['port'];
        }

        return [
        'base_uri' => $baseUri,
        'path' => (!empty($urlComponents['path'])) ? $urlComponents['path'] : '',
        ];
    }

    /**
     * gets the history of the requests sent to web services.
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
        $this->uri .= $request->getRequestURI();

        try {
            if ($this->logsManager->isInfo()) {
                $this->logsManager->logInfo(
                    $method.' request sent to '.$this->uri.'.',
                    ['requestOptions' => $requestOptions]
                );
            }

            return $this->HTTPClient->request($method, $this->uri, $requestOptions);
        } catch (ServerException $serverException) {
            $this->logsManager->logError('The Europa Search service returns an exception: '.$serverException->getMessage(), ['exception' => $serverException]);
            throw new ConnectionException('The connection to the service fails', $serverException);
        } catch (ClientException $clientException) {
            $this->logsManager->logError('The Transporter object returns an exception: '.$clientException->getMessage(), ['exception' => $clientException]);
            throw new WebServiceErrorException('The request sent to the service returned an error', $clientException);
        }
    }
}
