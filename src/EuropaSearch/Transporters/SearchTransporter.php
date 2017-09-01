<?php

/**
 * @file
 * Contains EC\EuropaSearch\Transporters\SearchTransporter.
 */

namespace EC\EuropaSearch\Transporters;

use EC\EuropaSearch\Messages\Search\SearchRequest;
use EC\EuropaWS\Exceptions\WebServiceErrorException;
use EC\EuropaWS\Messages\RequestInterface;
use GuzzleHttp\Exception\ClientException;
use EC\EuropaWS\Exceptions\ConnectionException;
use GuzzleHttp\Exception\ServerException;

/**
 * Class SearchTransporter.
 *
 * In charge to send the requests to the Search REST services of
 * Europa Search (Search API).
 *
 * @package EC\EuropaSearch\Transporters
 */
class SearchTransporter extends AbstractTransporter
{

    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request)
    {

        if ($request instanceof SearchRequest) {
            return $this->sendSearchRequest($request);
        }
    }

    /**
     * Send a search request to the Europa Search REST service.
     *
     * @param SearchRequest $request
     *   The search request.
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *   The raw service response.
     *
     * @throws ConnectionException
     *   If the connection with the services failed.
     * @throws WebServiceErrorException
     *   If the services returns an error.
     */
    private function sendSearchRequest(SearchRequest $request)
    {

        try {
            $requestOptions = [
                'multipart' => $request->getRequestBody(),
                'query' => $request->getRequestQuery(),
            ];

            return $this->HTTPClient->request('POST', '/es/search-api/rest/search', $requestOptions);
        } catch (ServerException $requestException) {
            throw new ConnectionException('The connection to the Search service fails', 289, $requestException);
        } catch (ClientException $requestException) {
            throw new WebServiceErrorException('The request sent to the Search service returned an error', 288, $requestException);
        }
    }
}
