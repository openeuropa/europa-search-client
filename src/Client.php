<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use League\Container\Container;
use OpenEuropa\EuropaSearchClient\Api\DeleteApi;
use OpenEuropa\EuropaSearchClient\Api\FacetApi;
use OpenEuropa\EuropaSearchClient\Api\FileIngestionApi;
use OpenEuropa\EuropaSearchClient\Api\InfoApi;
use OpenEuropa\EuropaSearchClient\Api\SearchApi;
use OpenEuropa\EuropaSearchClient\Api\TextIngestionApi;
use OpenEuropa\EuropaSearchClient\Api\TokenApi;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;
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
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @var \League\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var \Psr\Http\Client\ClientInterface
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
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
        $this->multipartStreamBuilder = new MultipartStreamBuilder($streamFactory);
        $this->serializer = new Serializer([
            new GetSetMethodNormalizer(
                null,
                new CamelCaseToSnakeCaseNameConverter(),
                new PhpDocExtractor()
            ),
            new ArrayDenormalizer(),
        ], [
            new JsonEncoder(),
        ]);
        $this->jsonEncoder = new JsonEncoder();
        $this->setConfiguration($configuration);
        $this->createContainer();
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
        return (new SearchApi(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
            $this->uriFactory,
            $this->multipartStreamBuilder,
            $this->serializer,
            $this->jsonEncoder,
            $this->configuration
        ))
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
        return (new FacetApi(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
            $this->uriFactory,
            $this->multipartStreamBuilder,
            $this->serializer,
            $this->jsonEncoder,
            $this->configuration['search'],
        ))
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
        return (new InfoApi(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
            $this->uriFactory,
            $this->multipartStreamBuilder,
            $this->serializer,
            $this->jsonEncoder,
            $this->configuration['info'],
        ))
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
        return (new TextIngestionApi(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
            $this->uriFactory,
            $this->multipartStreamBuilder,
            $this->serializer,
            $this->jsonEncoder,
            $this->configuration['textIngestion'],
        ))
            ->setTokenService($this->container->get('token'))
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
        return (new FileIngestionApi(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
            $this->uriFactory,
            $this->multipartStreamBuilder,
            $this->serializer,
            $this->jsonEncoder,
            $this->configuration['fileIngestion'],
        ))
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
        return (new DeleteApi(
            $this->httpClient,
            $this->requestFactory,
            $this->streamFactory,
            $this->uriFactory,
            $this->multipartStreamBuilder,
            $this->serializer,
            $this->jsonEncoder,
            $this->configuration['deleteDocument'],
        ))
            ->setReference($reference)
            ->execute();
    }

    private function setConfiguration(array $configuration): void
    {
        if (isset($configuration['searchApiEndpoint'])) {
            $this->configuration['search'] = [
                'searchApiEndpoint' => $configuration['searchApiEndpoint'],
                'apiKey' => $configuration['apiKey'],
                'database' => $configuration['database'],
            ];
        }
        if (isset($configuration['facetApiEndpoint'])) {
            $this->configuration['facet'] = [
                'facetApiEndpoint' => $configuration['facetApiEndpoint'],
                'apiKey' => $configuration['apiKey'],
                'database' => $configuration['database'],
            ];
        }
        if (isset($configuration['textIngestionApiEndpoint'])) {
            $this->configuration['textIngestion'] = [
                'textIngestionApiEndpoint' => $configuration['textIngestionApiEndpoint'],
                'apiKey' => $configuration['apiKey'],
                'database' => $configuration['database'],
            ];
        }
        if (isset($configuration['fileIngestionApiEndpoint'])) {
            $this->configuration['fileIngestion'] = [
                'fileIngestionApiEndpoint' => $configuration['fileIngestionApiEndpoint'],
                'apiKey' => $configuration['apiKey'],
                'database' => $configuration['database'],
            ];
        }
        if (isset($configuration['deleteApiEndpoint'])) {
            $this->configuration = [
                'deleteApiEndpoint' => $configuration['deleteApiEndpoint'],
                'apiKey' => $configuration['apiKey'],
                'database' => $configuration['database'],
            ];
        }
        if (isset($configuration['tokenApiEndpoint'])) {
            $this->configuration['token'] = [
                'tokenApiEndpoint' => $configuration['tokenApiEndpoint'],
                'consumerKey' => $configuration['consumerKey'],
                'consumerSecret' => $configuration['consumerSecret'],
            ];
        }
    }

    private function createContainer(): void
    {
        $container = new Container();
        $container->add('token', TokenApi::class)
            ->withArguments([
                $this->httpClient,
                $this->requestFactory,
                $this->streamFactory,
                $this->uriFactory,
                $this->multipartStreamBuilder,
                $this->serializer,
                $this->jsonEncoder,
                $this->configuration['token'],
            ]);
        $this->container = $container;
    }
}
