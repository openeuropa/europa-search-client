<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use League\Container\Argument\RawArgument;
use League\Container\Container;
use League\Container\ContainerAwareTrait;
use OpenEuropa\EuropaSearchClient\Api\Ingestion;
use OpenEuropa\EuropaSearchClient\Api\Search;
use OpenEuropa\EuropaSearchClient\Api\Token;
use OpenEuropa\EuropaSearchClient\Contract\ApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchInterface;
use OpenEuropa\EuropaSearchClient\Model\SearchResult;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
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
    ): SearchResult {
        return $this->getSearch()
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
            ->addArgument($streamFactory);
        $container->share('reflectionExtractor', ReflectionExtractor::class);
        $container->share('camelCaseToSnakeCaseNameConverter', CamelCaseToSnakeCaseNameConverter::class);
        $container->share('getSetMethodNormalizer', GetSetMethodNormalizer::class)
            ->addArguments([
                null,
                'camelCaseToSnakeCaseNameConverter',
                'reflectionExtractor',
            ]);
        $container->share('jsonEncoder', JsonEncoder::class);
        $container->share('serializer', Serializer::class)
            ->addArguments([
                new RawArgument([
                    $container->get('getSetMethodNormalizer'),
                ]),
                new RawArgument([
                    $container->get('jsonEncoder'),
                ])
            ]);
        $container->share('search', Search::class);
        $container->share('token', Token::class);
        $container->share('ingestion', Ingestion::class)
            ->addMethodCall('setToken', ['token']);

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
     * @return \OpenEuropa\EuropaSearchClient\Contract\SearchInterface
     */
    protected function getSearch(): SearchInterface
    {
        return $this->getContainer()->get('search');
    }
}
