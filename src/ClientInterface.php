<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient;

use OpenEuropa\EuropaSearchClient\Api\IngestionApi;
use OpenEuropa\EuropaSearchClient\Api\SearchApi;
use OpenEuropa\EuropaSearchClient\Model\Search;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Interface for clients that interact with Europa Search API.
 */
interface ClientInterface
{

    /**
     * Returns the HTTP client that is used for requests.
     *
     * @return \Psr\Http\Client\ClientInterface The HTTP client.
     *   The HTTP client.
     */
    public function getHttpClient(): HttpClientInterface;

    /**
     * Returns the request factory.
     *
     * @return \Psr\Http\Message\RequestFactoryInterface
     *   The request factory.
     */
    public function getRequestFactory(): RequestFactoryInterface;

    /**
     * Returns the stream factory.
     *
     * @return \Psr\Http\Message\StreamFactoryInterface
     *   The request factory.
     */
    public function getStreamFactory(): StreamFactoryInterface;

    /**
     * Returns the client configuration.
     *
     * @param string|null $name
     *   The configuration name. Returns all the configuration if empty.
     *
     * @return mixed
     *   The client configuration.
     */
    public function getConfiguration(string $name = null);

    /**
     * Returns the Ingestion Api.
     *
     * @return IngestionApi
     *   The Ingestion Api class
     */
    public function createIngestion() : IngestionApi;

    /**
     * Returns the Search Api.
     *
     * @return SearchApi
     *   The Search Api class
     */
    public function createSearch() : SearchApi;

    /**
     * Returns the Token Api.
     *
     * @return TokenApi
     *   The Token Api class
     */
    public function createToken() : TokenApi;
}
