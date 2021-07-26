<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\ApiInterface;
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
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Base class for Europa Search APIs.
 */
abstract class ApiBase implements ApiInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var OptionsResolver
     */
    protected $optionResolver;

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
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(
        array $configuration,
        OptionsResolver $optionsResolver,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory,
        MultipartStreamBuilder $multipartStreamBuilder,
        SerializerInterface $serializer,
        EncoderInterface $jsonEncoder)
    {
        $this->configuration = $configuration;
        $this->optionResolver = $optionsResolver;
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
        $this->multipartStreamBuilder = $multipartStreamBuilder;
        $this->serializer = $serializer;
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * @inheritDoc
     */
    public function setConfiguration(array $configuration): ApiInterface
    {
        $validSchemaKeys = ['type', 'required', 'default', 'value'];
        $configSchema = $this->getConfigSchema();

        // Keep only configurations defined in schema.
        $configuration = array_intersect_key($configuration, $configSchema);

        foreach ($configSchema as $configKey => $schema) {
            if ($invalidSchemaKeys = array_diff_key($schema, array_flip($validSchemaKeys))) {
                throw new \InvalidArgumentException("The configuration schema of '" . __CLASS__ . "' API contains invalid keys: '" . implode(', ', array_keys($invalidSchemaKeys)) . "'.");
            }
            $method = empty($schema['required']) ? 'setDefined' : 'setRequired';
            $this->optionResolver
                ->{$method}($configKey)
                ->addAllowedTypes($configKey, $schema['type']);
            if (isset($schema['default'])) {
                $this->optionResolver->setDefault($configKey, $schema['default']);
            }
            if (isset($schema['value'])) {
                $this->optionResolver->setAllowedValues($configKey, $schema['value']);
            }
        }

        $this->configuration = $this->optionResolver->resolve($configuration);

        return $this;
    }

    /**
     * @param string $configKey
     * @return mixed
     */
    protected function getConfigValue(string $configKey)
    {
        if (!isset($this->configuration[$configKey])) {
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
     * @return string
     */
    abstract protected function getEndpointUri(): string;

    /**
     *
     * @return string
     */
    protected function getRequestUri(): string
    {
        $uri = $this->uriFactory->createUri($this->getEndpointUri());
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

        // This API didn't define a request body.
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
     * @return array
     * @see ApiInterface::getConfigSchema()
     */
    protected function getEndpointSchema(): array
    {
        return [
            'type' => 'string',
            'required' => true,
            'value' => function (string $value) {
                return filter_var($value, FILTER_VALIDATE_URL);
            },
        ];
    }

    /**
     * @return array
     * @see ApiInterface::getConfigSchema()
     */
    protected function getRequiredStringSchema(): array
    {
        return [
            'type' => 'string',
            'required' => true,
        ];
    }
}
