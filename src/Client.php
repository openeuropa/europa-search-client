<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient;

use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The client to interface with the Europa Search API calls.
 *
 * @todo Add methods to allow fluent calls: $client->ingestion()->ingestText(...)
 * @todo Rename "_endpoint" options as they represent servers.
 */
class Client implements ClientInterface
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
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     *   The option resolver.
     */
    protected function getOptionResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();

        // All options are strings and required.
        $options = [
            'apiKey',
            'database',
            'ingestion_api_endpoint',
            'search_api_endpoint',
            'token_api_endpoint',
        ];
        foreach ($options as $option) {
            $resolver->setRequired($option)->setAllowedTypes($option, 'string');
        }

        return $resolver;
    }
}
