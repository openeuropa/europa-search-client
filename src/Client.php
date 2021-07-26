<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use League\Container\Container;
use League\Container\ContainerAwareTrait;
use OpenEuropa\EuropaSearchClient\Api\DeleteApi;
use OpenEuropa\EuropaSearchClient\Api\FacetApi;
use OpenEuropa\EuropaSearchClient\Api\FileIngestionApi;
use OpenEuropa\EuropaSearchClient\Api\InfoApi;
use OpenEuropa\EuropaSearchClient\Api\SearchApi;
use OpenEuropa\EuropaSearchClient\Api\TextIngestionApi;
use OpenEuropa\EuropaSearchClient\Api\TokenApi;
use OpenEuropa\EuropaSearchClient\Contract\ApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;
use OpenEuropa\EuropaSearchClient\Contract\DeleteApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\FileIngestionApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\InfoApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\TextIngestionApiInterface;
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
    use ContainerAwareTrait;

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
        $this->createContainer(
            $httpClient,
            $requestFactory,
            $streamFactory,
            $uriFactory,
            $configuration
        );
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
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
        return $this->getSearchService()
            ->setText($text)
            ->setLanguages($languages)
            ->setQuery($query)
            ->setSort(new Sort($sortField, $sortOrder))
            ->setPageNumber($pageNumber)
            ->setPageSize($pageSize)
            ->setHighlightRegex($highlightRegex)
            ->setHighlightLimit($highlightLimit)
            ->setSessionToken($sessionToken)
            ->search();
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
        return $this->getFacetService()
            ->setText($text)
            ->setLanguages($languages)
            ->setDisplayLanguage($displayLanguage)
            ->setQuery($query)
            ->setFacetSort($facetSort)
            ->setSessionToken($sessionToken)
            ->getFacets();
    }

    /**
     * @return Info
     */
    public function getInfo(): Info
    {
        return $this->getInfoService()
            ->getInfo();
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
        return $this->getTextIngestionService()
            ->setUri($this->getUriFactory()->createUri($uri))
            ->setText($text)
            ->setLanguages($languages)
            ->setMetadata(new Metadata($metadata))
            ->setReference($reference)
            ->setAclUsers($aclUsers)
            ->setAclNoUsers($aclNoUsers)
            ->setAclGroups($aclGroups)
            ->setAclNoGroups($aclNoGroups)
            ->ingest();
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
        return $this->getFileIngestionService()
            ->setUri($this->getUriFactory()->createUri($uri))
            ->setFile($file)
            ->setLanguages($languages)
            ->setMetadata(new Metadata($metadata))
            ->setReference($reference)
            ->setAclUsers($aclUsers)
            ->setAclNoUsers($aclNoUsers)
            ->setAclGroups($aclGroups)
            ->setAclNoGroups($aclNoGroups)
            ->ingest();
    }

    /**
     * @inheritDoc
     */
    public function deleteDocument(string $reference): bool
    {
        return $this->getDeleteService()
            ->setReference($reference)
            ->deleteDocument();
    }

    /**
     * @param HttpClientInterface     $httpClient
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface  $streamFactory
     * @param UriFactoryInterface     $uriFactory,
     * @param array                   $configuration
     */
    private function createContainer(
        HttpClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory,
        array $configuration
    ): void {
        $container = new Container();
        $container->share('uriFactory', $uriFactory);

        // API services are not shared, meaning that a new instance is created
        // every time the service is requested from the container. We're doing
        // this because such a service might be called more than once during the
        // lifetime of a request, so internals set in a previous usage may leak
        // into the later usages.
        $container->add('optionResolver', OptionsResolver::class);
        $container->add('search', SearchApi::class);
        $container->add('facet', FacetApi::class);
        $container->add('info', InfoApi::class);
        $container->add('token', TokenApi::class);
        $container->add('textIngestion', TextIngestionApi::class);
        $container->add('fileIngestion', FileIngestionApi::class);
        $container->add('deleteDocument', DeleteApi::class);

        // Inject the token service for APIs that are requesting it.
        $container->inflector(TokenAwareInterface::class)
            ->invokeMethod('setTokenService', ['token']);

        // Inject the services into APIs.
        $container->inflector(ApiInterface::class)
            ->invokeMethods([
                'setOptionsResolver' => ['optionResolver'],
                'setConfiguration' => [$configuration],
                'setHttpClient' => [$httpClient],
                'setRequestFactory' => [$requestFactory],
                'setStreamFactory' => [$streamFactory],
                'setUriFactory' => ['uriFactory'],
                'setMultipartStreamBuilder' => [new MultipartStreamBuilder($streamFactory)],
                'setSerializer' => [new Serializer([
                    new GetSetMethodNormalizer(
                        null,
                        new CamelCaseToSnakeCaseNameConverter(),
                        new PhpDocExtractor()
                    ),
                    new ArrayDenormalizer(),
                ], [
                    new JsonEncoder(),
                ])],
                'setJsonEncoder' => [new JsonEncoder()],
            ]);

        // Keep a reference to the container.
        $this->setContainer($container);
    }

    /**
     * @return SearchApiInterface
     */
    protected function getSearchService(): SearchApiInterface
    {
        return $this->getContainer()->get('search');
    }

    /**
     * @return \OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface
     */
    protected function getFacetService(): FacetApiInterface
    {
        return $this->getContainer()->get('facet');
    }

    /**
     * @return InfoApiInterface
     */
    protected function getInfoService(): InfoApiInterface
    {
        return $this->getContainer()->get('info');
    }

    /**
     * @return TextIngestionApiInterface
     */
    protected function getTextIngestionService(): TextIngestionApiInterface
    {
        return $this->getContainer()->get('textIngestion');
    }

    /**
     * @return FileIngestionApiInterface
     */
    protected function getFileIngestionService(): FileIngestionApiInterface
    {
        return $this->getContainer()->get('fileIngestion');
    }

    /**
     * @return DeleteApiInterface
     */
    protected function getDeleteService(): DeleteApiInterface
    {
        return $this->getContainer()->get('deleteDocument');
    }

    /**
     * @return UriFactoryInterface
     */
    protected function getUriFactory(): UriFactoryInterface
    {
        return $this->getContainer()->get('uriFactory');
    }
}
