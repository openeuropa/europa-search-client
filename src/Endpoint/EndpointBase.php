<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\EndpointInterface;
use OpenEuropa\EuropaSearchClient\Exception\InvalidStatusCodeException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Base class for Europa Search API endpoints.
 */
abstract class EndpointBase implements EndpointInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var RequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    protected $streamFactory;

    /**
     * @var UriFactoryInterface
     */
    protected $uriFactory;

    /**
     * @var MultipartStreamBuilder
     */
    protected $multipartStreamBuilder;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(string $endpointUrl, array $configuration = [])
    {
        $configuration['endpointUrl'] = $endpointUrl;
        $this->configuration = $this->getConfigurationResolver()->resolve($configuration);
    }

    /**
     * @inheritDoc
     */
    public function setHttpClient(ClientInterface $httpClient): EndpointInterface
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRequestFactory(RequestFactoryInterface $requestFactory): EndpointInterface
    {
        $this->requestFactory = $requestFactory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setStreamFactory(StreamFactoryInterface $streamFactory): EndpointInterface
    {
        $this->streamFactory = $streamFactory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUriFactory(UriFactoryInterface $uriFactory): EndpointInterface
    {
        $this->uriFactory = $uriFactory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setMultipartStreamBuilder(MultipartStreamBuilder $multipartStreamBuilder): EndpointInterface
    {
        $this->multipartStreamBuilder = $multipartStreamBuilder;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setJsonEncoder(EncoderInterface $jsonEncoder): EndpointInterface
    {
        $this->jsonEncoder = $jsonEncoder;
        return $this;
    }

    /**
     * Returns an option resolver configured to validate the configuration.
     *
     * @return OptionsResolver
     */
    protected function getConfigurationResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired('endpointUrl')
            ->setAllowedTypes('endpointUrl', 'string')
            ->setAllowedValues('endpointUrl', function (string $value) {
                return filter_var($value, FILTER_VALIDATE_URL);
            });

        return $resolver;
    }

    /**
     * @param string $configKey
     * @return mixed
     */
    protected function getConfigValue(string $configKey)
    {
        if (!array_key_exists($configKey, $this->configuration)) {
            throw new \InvalidArgumentException("Invalid config key: '{$configKey}'. Valid keys: '" . implode("', '", array_keys($this->configuration)) . "'.");
        }
        return $this->configuration[$configKey];
    }

    /**
     * Sends a request and return its response.
     *
     * @param string $method
     *   The request method.
     *
     * @return ResponseInterface
     *   The response.
     *
     * @throws ClientExceptionInterface
     *   Thrown if a network error happened while processing the request.
     * @throws InvalidStatusCodeException
     *   Thrown when the API endpoint returns code other than 200.
     */
    protected function send(string $method): ResponseInterface
    {
        $method = strtoupper($method);
        $uri = $this->getRequestUri();
        $request = $this->requestFactory->createRequest($method, $uri);

        $methodsWithBody = ['POST', 'PUT', 'PATCH'];
        if (in_array($method, $methodsWithBody) && $body = $this->getRequestBody()) {
            $request = $request->withBody($body);
        }

        if ($headers = $this->getRequestHeaders()) {
            foreach ($headers as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
        }

        $response = $this->httpClient->sendRequest($request);

        if (!in_array($response->getStatusCode(), [200, 201], true)) {
            throw new InvalidStatusCodeException("{$method} {$uri} returns {$response->getStatusCode()}");
        }

        return $response;
    }

    /**
     *
     * @return string
     */
    protected function getRequestUri(): string
    {
        $uri = $this->uriFactory->createUri($this->getConfigValue('endpointUrl'));
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
        // Methods building the request URI or body may store additional headers
        // in $this->headers array, as they act before this method.
        return $this->headers;
    }

    /**
     * @return \Psr\Http\Message\StreamInterface|null
     */
    protected function getRequestBody(): ?StreamInterface
    {
        // Multipart stream has precedence, if it has been defined.
        if ($parts = $this->getRequestMultipartStreamElements()) {
            foreach ($parts as $name => $part) {
                $contentType = $part['contentType'] ?? 'application/json';
                $this->multipartStreamBuilder->addResource($name, $part['content'], [
                    'headers' => [
                        'Content-Type' => $contentType,
                    ],
                    'filename' => $name,
                ]);
            }

            // The multipart stream needs inform the server about the
            // Content-Type and  multipart parts boundary ID.
            $this->headers['Content-Type'] = 'multipart/form-data; boundary="' . $this->multipartStreamBuilder->getBoundary() . '"';

            return $this->multipartStreamBuilder->build();
        }

        // Simple form elements.
        if ($parts = $this->getRequestFormElements()) {
            // Give server guidance on how to decode the stream.
            $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
            return $this->streamFactory->createStream(http_build_query($parts));
        }

        // This endpoint didn't define a request body.
        return null;
    }

    /**
     * @return array
     *   Associative array of multipart parts keyed by the part name. The values
     *   are associative arrays with two keys:
     *   - content (string): The multipart part contents.
     *   - contentType (string, optional): The multipart part content type. If
     *     omitted, 'application/json' is assumed.
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

    /**
     * Returns a serializer configured to decode the endpoint response.
     *
     * @return SerializerInterface
     */
    protected function getSerializer(): SerializerInterface
    {
        return new Serializer([
            new GetSetMethodNormalizer(
                null,
                new CamelCaseToSnakeCaseNameConverter(),
                new PhpDocExtractor()
            ),
            new ArrayDenormalizer(),
        ], [
            new JsonEncoder(),
        ]);
    }
}
