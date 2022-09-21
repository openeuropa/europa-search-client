<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use League\Container\Argument\RawArgument;
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
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
        $sortField = null,
        ?string $sortOrder = null,
        ?int $pageNumber = null,
        ?int $pageSize = null,
        ?string $highlightRegex = null,
        ?int $highlightLimit = null,
        ?string $sessionToken = null
    ): Search {
        /** @var SearchEndpoint $endpoint */
        $endpoint = $this->container->get('search');

        $sort = [];
        if (is_array($sortField) && !is_null($sortOrder)) {
            throw new \InvalidArgumentException('$sortOrder should be NULL when $sortField is an array');
        }

        if (is_array($sortField) && is_null($sortOrder)) {
            foreach ($sortField as $field => $order) {
                $sort[] = new Sort($field, $order);
            }
        } elseif (!empty($sortField)) {
            $sort[] = new Sort($sortField, $sortOrder);
        }

        return $endpoint
            ->setText($text)
            ->setLanguages($languages)
            ->setQuery($query)
            ->setSort($sort)
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
        /** @var FacetEndpoint $endpoint */
        $endpoint = $this->container->get('facet');
        return $endpoint
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
        /** @var InfoEndpoint $endpoint */
        $endpoint = $this->container->get('info');
        return $endpoint->execute();
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
        /** @var TextIngestionEndpoint $endpoint */
        $endpoint = $this->container->get('textIngestion');
        return $endpoint
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
        /** @var FileIngestionEndpoint $endpoint */
        $endpoint = $this->container->get('fileIngestion');
        return $endpoint
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
        /** @var DeleteEndpoint $endpoint */
        $endpoint = $this->container->get('deleteDocument');
        return $endpoint
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

        $container->add('database_config', new RawArgument($this->extractConfigValues([
            'apiKey',
            'database',
        ])));
        $container->add('token_config', new RawArgument($this->extractConfigValues([
            'consumerKey',
            'consumerSecret',
        ])));

        // All services are not shared, meaning that a new instance is
        // created every time the service is requested from the container.
        // We're doing this because such a service might be called more than
        // once during the lifetime of a request, so internals set in a previous
        // usage may leak into the later usages.
        $container->add('multipartStreamBuilder', MultipartStreamBuilder::class)
            ->addArgument($streamFactory);
        $container->add('search', SearchEndpoint::class)
            ->addArguments([
                new RawArgument($this->getConfigValue('searchApiEndpoint')),
                'database_config',
            ]);
        $container->add('facet', FacetEndpoint::class)
            ->addArguments([
                new RawArgument($this->getConfigValue('facetApiEndpoint')),
                'database_config',
            ]);
        $container->add('info', InfoEndpoint::class)
            ->addArgument(new RawArgument($this->getConfigValue('infoApiEndpoint')));
        $container->add('textIngestion', TextIngestionEndpoint::class)
            ->addArguments([
                new RawArgument($this->getConfigValue('textIngestionApiEndpoint')),
                'database_config',
            ]);
        $container->add('fileIngestion', FileIngestionEndpoint::class)
            ->addArguments([
                new RawArgument($this->getConfigValue('fileIngestionApiEndpoint')),
                'database_config',
            ]);
        $container->add('deleteDocument', DeleteEndpoint::class)
            ->addArguments([
                new RawArgument($this->getConfigValue('deleteApiEndpoint')),
                'database_config',
            ]);
        $container->add('token', TokenEndpoint::class)
            ->addArguments([
                new RawArgument($this->getConfigValue('tokenApiEndpoint')),
                'token_config',
            ]);

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
     * Extracts a subset of values from the client configuration.
     *
     * Non-existing keys are not returned.
     *
     * @param array $names
     *   A list of configuration keys to extract.
     * @return array
     */
    private function extractConfigValues(array $names): array
    {
        $config = [];

        foreach ($names as $name) {
            // We do not use self::getConfigValue() as we need to prevent
            // adding NULL for non-existing values.
            if (array_key_exists($name, $this->configuration)) {
                $config[$name] = $this->configuration[$name];
            }
        }

        return $config;
    }

    /**
     * Retrieves a value from the client configuration.
     *
     * @param string $name
     * @return mixed|null
     */
    private function getConfigValue(string $name)
    {
        return array_key_exists($name, $this->configuration) ? $this->configuration[$name] : null;
    }
}
