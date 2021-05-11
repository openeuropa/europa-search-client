<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient;

use GuzzleHttp\Exception\ClientException;
use OpenEuropa\EuropaSearchClient\Api\IngestionApi;
use OpenEuropa\EuropaSearchClient\Api\SearchApi;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The client to interface with the Europa Search API calls.
 *
 * @todo Rename "_endpoint" options as they represent servers.
 */
class Client implements ClientInterface, RequestFactoryInterface, StreamFactoryInterface
{

    /**
     * Extra client configuration.
     *
     * @var array
     */
    protected $configuration;

    /**
     * The client to send HTTP requests.
     *
     * @var \Psr\Http\Client\ClientInterface
     */
    protected $httpClient;

    /**
     * The request factory.
     *
     * @var \Psr\Http\Message\RequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * The stream factory.
     *
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    protected $streamFactory;

    /**
     * Client constructor.
     *
     * @param \Psr\Http\Client\ClientInterface $httpClient
     *   The client to send HTTP requests.
     * @param \Psr\Http\Message\RequestFactoryInterface $requestFactory
     *   The request factory.
     * @param \Psr\Http\Message\StreamFactoryInterface $streamFactory
     *   The stream factory.
     * @param array $configuration
     *   The client configuration.
     */
    public function __construct(
        HttpClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        array $configuration = []
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;

        $this->configuration = $this->getOptionResolver()->resolve($configuration);
    }

    /**
     * @inheritDoc
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @inheritDoc
     */
    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    /**
     * @inheritDoc
     */
    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function getConfiguration(string $name = null)
    {
        if ($name !== null) {
            return $this->configuration[$name] ?? null;
        }

        return $this->configuration;
    }

    /**
     * Returns a configured option resolver.
     *
     * @return \Symfony\Component\OptionsResolver\Options
     *   The option resolver.
     */
    protected function getOptionResolver(): Options
    {
        $resolver = new OptionsResolver();

        // All options are strings and required.
        $options = [
            'apiKey',
            'database',
            'ingestionApiEndpoint',
            'searchApiEndpoint',
        ];
        foreach ($options as $option) {
            $resolver->setRequired($option)->setAllowedTypes($option, 'string');
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return $this->requestFactory->createRequest($method, $uri);
    }

    /**
     * @inheritDoc
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return $this->streamFactory->createStream($content);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return $this->streamFactory->createStreamFromFile($filename, $mode);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return $this->streamFactory->createStreamFromResource($resource);
    }

    /**
     * @inheritDoc
     */
    public function createIngestion(): IngestionApi
    {
        return new IngestionApi($this);
    }

    /**
     * @inheritDoc
     */
    public function createSearch($resource): SearchApi
    {
        return new SearchApi($this);
    }

    /**
     * Pings the search api endpoint
     *
     * @return bool
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function ping()
    {
        $request = $this->createRequest('GET', 'https://api.tech.ec.europa.eu/search-api/acc/info');

        try {
            $response = $this->getHttpClient()->sendRequest($request);
            return $response->getStatusCode() == 200;
        }
        catch (ClientException $e) {
            return false;
        }
    }
}
