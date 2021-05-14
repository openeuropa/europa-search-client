<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use League\Container\ContainerAwareTrait;
use OpenEuropa\EuropaSearchClient\Contract\ApiInterface;
use OpenEuropa\EuropaSearchClient\Traits\ServicesTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Base class for Europa Search API requests.
 */
abstract class ApiBase implements ApiInterface
{
    use ContainerAwareTrait;
    use ServicesTrait;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * Sends a request and return its response.
     *
     * @param string $method
     *   The request method.
     *
     * @return \Psr\Http\Message\ResponseInterface
     *   The response.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    protected function send(string $method): ResponseInterface
    {
        $method = strtoupper($method);
        $request = $this->getRequestFactory()->createRequest(
            $method,
            $this->getRequestUri()
        );

        if ($headers = $this->getRequestHeaders()) {
            foreach ($headers as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
        }

        $methodsWithBody = ['POST', 'PUT', 'PATCH'];
        if (in_array($method, $methodsWithBody) && $body = $this->getRequestBody()) {
            $request = $request->withBody($body);
        }

        return $this->getHttpClient()->sendRequest($request);
    }

    /**
     * @inheritDoc
     */
    public function buildConfigurationSchema(): void
    {
        $this->getOptionResolver()
            ->setRequired(['apiKey'])
            ->setAllowedTypes('apiKey', 'string');
    }

    /**
     * @return string
     */
    abstract protected function getEndpointUri(): string;

    /**
     *
     * @return string
     */
    protected function getRequestUri(): string
    {
        $uri = $this->getUriFactory()->createUri($this->getEndpointUri());
        $query = $this->getRequestUriQuery($uri);
        return $uri->withQuery(http_build_query($query))->__toString();
    }

    /**
     * @param \Psr\Http\Message\UriInterface $uri
     *
     * @return array
     */
    protected function getRequestUriQuery(UriInterface $uri): array
    {
        $query = [];
        if ($queryString = $uri->getQuery()) {
            parse_str($queryString, $apiEndpointQuery);
            $query += $apiEndpointQuery;
        }
        return $query;
    }

    /**
     * @return array
     */
    protected function getRequestHeaders(): array
    {
        return [];
    }

    /**
     * @return \Psr\Http\Message\StreamInterface|null
     */
    protected function getRequestBody(): ?StreamInterface
    {
        // Multipart stream has precedence.
        if ($parts = $this->getRequestMultipartStreamElements()) {
            $builder = $this->getMultipartStreamBuilder();
            foreach ($parts as $name => $contents) {
                $builder->addResource($name, $contents, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'filename' => 'blob',
                ]);
            }
            return $builder->build();
        }
        if ($parts = $this->getRequestFormElements()) {
            return $this->getStreamFactory()->createStream(http_build_query($parts));
        }
        return null;
    }

    /**
     * @return array
     */
    protected function getRequestMultipartStreamElements(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getRequestFormElements(): array
    {
        return [];
    }
}
