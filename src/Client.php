<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use League\Container\Argument\RawArgument;
use League\Container\Container;
use League\Container\ContainerAwareTrait;
use OpenEuropa\EuropaSearchClient\Api\DeleteApi;
use OpenEuropa\EuropaSearchClient\Api\SearchApi;
use OpenEuropa\EuropaSearchClient\Api\TextIngestionApi;
use OpenEuropa\EuropaSearchClient\Api\TokenApi;
use OpenEuropa\EuropaSearchClient\Contract\ApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;
use OpenEuropa\EuropaSearchClient\Contract\DeleteApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\TextIngestionApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\Search;
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
     */
    public function search(
        ?string $text = null,
        ?array $languages = null,
        ?array $query = null,
        ?array $sort = null,
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
            ->setSort($sort)
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
    public function ingestText(
        string $uri,
        ?string $text,
        ?array $languages = null,
        ?array $metadata = null,
        ?string $reference = null
    ): Ingestion {
        return $this->getTextIngestionService()
            ->setUri($uri)
            ->setText($text)
            ->setLanguages($languages)
            ->setMetadata($metadata)
            ->setReference($reference)
            ->ingest();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $reference): bool
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
        $container->share('optionResolver', OptionsResolver::class);
        $container->share('httpClient', $httpClient);
        $container->share('requestFactory', $requestFactory);
        $container->share('streamFactory', $streamFactory);
        $container->share('uriFactory', $uriFactory);
        $container->share('multipartStreamBuilder', MultipartStreamBuilder::class)
            ->withArgument($streamFactory);
        $container->share('jsonEncoder', JsonEncoder::class);
        $container->share('serializer', Serializer::class)
            ->withArgument([
                new GetSetMethodNormalizer(
                    null,
                    new CamelCaseToSnakeCaseNameConverter(),
                    new PhpDocExtractor()
                ),
                new ArrayDenormalizer(),
            ])
            ->withArgument(new RawArgument([$container->get('jsonEncoder')]));

        // API services are not shared, meaning that a new instance is created
        // every time the service is requested from the container.
        $container->add('search', SearchApi::class);
        $container->add('token', TokenApi::class);
        $container->add('textIngestion', TextIngestionApi::class);
        $container->add('deleteDocument', DeleteApi::class);

        // Inject the token service for APIs that are requesting it.
        $container->inflector(TokenAwareInterface::class)
            ->invokeMethod('setTokenService', ['token']);

        // Inject the services into APIs.
        $container->inflector(ApiInterface::class)
            ->invokeMethods([
                'setOptionsResolver' => ['optionResolver'],
                'setConfiguration' => [$configuration],
                'setHttpClient' => ['httpClient'],
                'setRequestFactory' => ['requestFactory'],
                'setStreamFactory' => ['streamFactory'],
                'setUriFactory' => ['uriFactory'],
                'setMultipartStreamBuilder' => ['multipartStreamBuilder'],
                'setSerializer' => ['serializer'],
                'setJsonEncoder' => ['jsonEncoder'],
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
     * @return TextIngestionApiInterface
     */
    protected function getTextIngestionService(): TextIngestionApiInterface
    {
        return $this->getContainer()->get('textIngestion');
    }

    /**
     * @return DeleteApiInterface
     */
    protected function getDeleteService(): DeleteApiInterface
    {
        return $this->getContainer()->get('deleteDocument');
    }
}
