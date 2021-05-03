<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient;

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
     * @return mixed|array
     *   The client configuration.
     */
    public function getConfiguration(string $name = null);
}
