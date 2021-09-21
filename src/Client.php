<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use League\Container\Container;
use OpenEuropa\EuropaSearchClient\Endpoint\DeleteEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\FacetEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\FileIngestionEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\InfoEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\SearchEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\TextIngestionEndpoint;
use OpenEuropa\EuropaSearchClient\Endpoint\TokenEndpoint;
use OpenEuropa\EuropaSearchClient\Contract\EndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;
use OpenEuropa\EuropaSearchClient\Contract\DeleteEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\FacetEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\FileIngestionEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\InfoEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\TextIngestionEndpointInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Model\Facets;
use OpenEuropa\EuropaSearchClient\Model\Info;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\Metadata;
use OpenEuropa\EuropaSearchClient\Model\Search;
use OpenEuropa\EuropaSearchClient\Model\Sort;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * The client to interface with the Europa Search API calls.
 *
 * This class acts like a proxy for the underlying APIs, such as search,
 * ingestion, token, facets, etc. The caller should instantiate this class as
 * the only entry-point.
 */
class Client implements ClientInterface
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var \League\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var UriFactoryInterface
     */
    protected $uriFactory;

    /**
     * @param HttpClientInterface     $httpClient
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface  $streamFactory
     * @param UriFactoryInterface     $uriFactory
     * @param array                   $configuration
     */
    public function __construct(
        HttpClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory,
        array $configuration
    ) {
        $this->uriFactory = $uriFactory;
        $this->configuration = $configuration;
        $this->createContainer(
            $httpClient,
            $requestFactory,
            $streamFactory,
            $uriFactory,
        );
    }

    /**
     * @inheritDoc
     */
    public function search(
        ?string $text = null,
        ?array $languages = null,
        ?array $query = null,
        ?string $sortField = null,
        ?string $sortOrder = null,
        ?int $pageNumber = null,
        ?int $pageSize = null,
        ?string $highlightRegex = null,
        ?int $highlightLimit = null,
        ?string $sessionToken = null
    ): Search {
        /** @var SearchEndpointInterface $endpoint */
        $endpoint = $this->container->get('search');
        return $endpoint
            ->setConfiguration($this->extractEndpointConfig([
                'endpointUrl' => 'searchApiEndpoint',
                'apiKey',
                'database',
            ]))
            ->setText($text)
            ->setLanguages($languages)
            ->setQuery($query)
            ->setSort(new Sort($sortField, $sortOrder))
            ->setPageNumber($pageNumber)
            ->setPageSize($pageSize)
            ->setHighlightRegex($highlightRegex)
            ->setHighlightLimit($highlightLimit)
            ->setSessionToken($sessionToken)
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function getFacets(
        ?string $text = null,
        ?array $languages = null,
        ?string $displayLanguage = null,
        ?array $query = null,
        ?string $facetSort = null,
        ?string $sessionToken = null
    ): Facets {
        /** @var FacetEndpointInterface $endpoint */
        $endpoint = $this->container->get('facet');
        return $endpoint
            ->setConfiguration($this->extractEndpointConfig([
                'endpointUrl' => 'facetApiEndpoint',
                'apiKey',
                'database',
            ]))
            ->setText($text)
            ->setLanguages($languages)
            ->setDisplayLanguage($displayLanguage)
            ->setQuery($query)
            ->setFacetSort($facetSort)
            ->setSessionToken($sessionToken)
            ->execute();
    }

    /**
     * @return Info
     */
    public function getInfo(): Info
    {
        /** @var InfoEndpointInterface $endpoint */
        $endpoint = $this->container->get('info');
        return $endpoint
            ->setConfiguration($this->extractEndpointConfig([
                'endpointUrl' => 'infoApiEndpoint',
            ]))
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function ingestText(
        string $uri,
        ?string $text = null,
        ?array $languages = null,
        ?array $metadata = null,
        ?string $reference = null,
        ?array $aclUsers = null,
        ?array $aclNoUsers = null,
        ?array $aclGroups = null,
        ?array $aclNoGroups = null
    ): Ingestion {
        /** @var TextIngestionEndpointInterface $endpoint */
        $endpoint = $this->container->get('textIngestion');
        return $endpoint
            ->setConfiguration($this->extractEndpointConfig([
                'endpointUrl' => 'textIngestionApiEndpoint',
                'apiKey',
                'database',
            ]))
            ->setUri($this->uriFactory->createUri($uri))
            ->setText($text)
            ->setLanguages($languages)
            ->setMetadata(new Metadata($metadata))
            ->setReference($reference)
            ->setAclUsers($aclUsers)
            ->setAclNoUsers($aclNoUsers)
            ->setAclGroups($aclGroups)
            ->setAclNoGroups($aclNoGroups)
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function ingestFile(
        string $uri,
        ?string $file = null,
        ?array $languages = null,
        ?array $metadata = null,
        ?string $reference = null,
        ?array $aclUsers = null,
        ?array $aclNoUsers = null,
        ?array $aclGroups = null,
        ?array $aclNoGroups = null
    ): Ingestion {
        /** @var FileIngestionEndpointInterface $endpoint */
        $endpoint = $this->container->get('fileIngestion');
        return $endpoint
            ->setConfiguration($this->extractEndpointConfig([
                'endpointUrl' => 'fileIngestionApiEndpoint',
                'apiKey',
                'database',
            ]))
            ->setUri($this->uriFactory->createUri($uri))
            ->setFile($file)
            ->setLanguages($languages)
            ->setMetadata(new Metadata($metadata))
            ->setReference($reference)
            ->setAclUsers($aclUsers)
            ->setAclNoUsers($aclNoUsers)
            ->setAclGroups($aclGroups)
            ->setAclNoGroups($aclNoGroups)
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function deleteDocument(string $reference): bool
    {
        /** @var DeleteEndpointInterface $endpoint */
        $endpoint = $this->container->get('deleteDocument');
        return $endpoint
            ->setConfiguration($this->extractEndpointConfig([
                'endpointUrl' => 'deleteApiEndpoint',
                'apiKey',
                'database',
            ]))
            ->setReference($reference)
            ->execute();
    }

    /**
     * @param HttpClientInterface     $httpClient
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface  $streamFactory
     * @param UriFactoryInterface     $uriFactory
     */
    private function createContainer(
        HttpClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory
    ): void {
        $container = new Container();
        // API endpoint services are not shared, meaning that a new instance is
        // created every time the service is requested from the container.
        // We're doing this because such a service might be called more than
        // once during the lifetime of a request, so internals set in a previous
        // usage may leak into the later usages.
        $container->add('multipartStreamBuilder', MultipartStreamBuilder::class)
            ->withArgument($streamFactory);
        $container->add('optionResolver', OptionsResolver::class);
        $container->add('search', SearchEndpoint::class);
        $container->add('facet', FacetEndpoint::class);
        $container->add('info', InfoEndpoint::class);
        $container->add('textIngestion', TextIngestionEndpoint::class);
        $container->add('fileIngestion', FileIngestionEndpoint::class);
        $container->add('deleteDocument', DeleteEndpoint::class);

        // Since the token service is injected into other endpoints,
        // set the configuration when it gets instantiated.
        $container->add('token', TokenEndpoint::class)
            ->withMethodCall('setConfiguration', [$this->getTokenEndpointConfiguration()]);

        // Inject the token service for endpoints that are requesting it.
        $container->inflector(TokenAwareInterface::class)
            ->invokeMethod('setTokenService', ['token']);

        // Inject the services into endpoints.
        $container->inflector(EndpointInterface::class)
            ->invokeMethods([
                'setHttpClient' => [$httpClient],
                'setRequestFactory' => [$requestFactory],
                'setStreamFactory' => [$streamFactory],
                'setUriFactory' => [$uriFactory],
                'setMultipartStreamBuilder' => ['multipartStreamBuilder'],
                'setJsonEncoder' => [new JsonEncoder()],
            ]);

        // Keep a reference to the container.
        $this->container = $container;
    }

    /**
     * Returns the configuration values for the token endpoint.
     *
     * @return array
     */
    private function getTokenEndpointConfiguration(): array
    {
        return $this->extractEndpointConfig([
            'endpointUrl' => 'tokenApiEndpoint',
            'consumerKey',
            'consumerSecret',
        ]);
    }

    /**
     * Extracts endpoint configuration from the client configuration.
     *
     * @param array $mappings
     *   A list of configuration keys to extract. If a string key is
     *   passed for an entry, that string will be used as name for
     *   the configuration value.
     * @return array
     */
    private function extractEndpointConfig(array $mappings): array
    {
        $config = [];

        foreach ($mappings as $finalName => $originalName) {
            $finalName = is_string($finalName) ? $finalName : $originalName;
            if (array_key_exists($originalName, $this->configuration)) {
                $config[$finalName] = $this->configuration[$originalName];
            }
        }

        return $config;
    }
}
