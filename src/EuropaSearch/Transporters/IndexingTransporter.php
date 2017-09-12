<?php

/**
 * @file
 * Contains EC\EuropaSearch\Transporters\IndexingTransporter.
 */

namespace EC\EuropaSearch\Transporters;

use EC\EuropaSearch\Messages\Index\WebContentRequest;
use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Exceptions\ConnectionException;
use EC\EuropaWS\Exceptions\WebServiceErrorException;
use EC\EuropaWS\Messages\RequestInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

/**
 * Class IndexingTransporter.
 *
 * In charge to send the requests to the Indexing REST services of
 * Europa Search (Ingestion API).
 *
 * @package EC\EuropaSearch\Transporters
 */
class IndexingTransporter extends AbstractTransporter
{

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
     * @throws WebServiceErrorException
     *   If the services returns an error.
     */
    private function sendWebContentRequest(WebContentRequest $request)
    {

        try {
            $requestOptions = [
                'multipart' => $request->getRequestBody(),
                'query' => $request->getRequestQuery(),
            ];

            return $this->HTTPClient->request('POST', '/es/ingestion-api/rest/ingestion/text', $requestOptions);
        } catch (ServerException $requestException) {
            throw new ConnectionException('The connection to the Ingestion service fails', $requestException);
        } catch (ClientException $requestException) {
            throw new WebServiceErrorException('The request sent to the Ingestion service returned an error', $requestException);
        }
    }
}
