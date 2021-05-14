<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\SearchInterface;
use OpenEuropa\EuropaSearchClient\Model\SearchResult;
use Psr\Http\Message\UriInterface;

/**
 * Class representing the Search API endpoints.
 */
class Search extends ApiBase implements SearchInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var string[]
     */
    protected $languages;

    /**
     * @var array
     */
    protected $query;

    /**
     * @var array
     */
    protected $sort;

    /**
     * @var int
     */
    protected $pageNumber;

    /**
     * @var int
     */
    protected $pageSize;

    /**
     * @var string
     */
    protected $highlightRegex;

    /**
     * @var int
     */
    protected $highlightLimit;

    /**
     * @var string
     */
    protected $sessionToken;

    /**
     * @inheritDoc
     */
    public function search(): SearchResult
    {
        /** @var SearchResult $search */
        $search = $this->getSerializer()->deserialize(
            $this->send('POST')->getBody()->__toString(),
            SearchResult::class,
            'json'
        );
        return $search;
    }

    /**
     * @inheritDoc
     */
    public function buildConfigurationSchema(): void
    {
        parent::buildConfigurationSchema();
        $this->getOptionResolver()
            ->setRequired(['searchApiEndpoint'])
            ->setAllowedTypes('searchApiEndpoint', 'string');
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfiguration()['searchApiEndpoint'];
    }

    /**
     * @inheritDoc
     */
    protected function getRequestUriQuery(UriInterface $uri): array
    {
        $query = parent::getRequestUriQuery($uri);

        $config = $this->getConfiguration();
        $query['apiKey'] = $config['apiKey'];
        $query['text'] = $this->getText();

        if ($pageNumber = $this->getPageNumber()) {
            $query['pageNumber'] = $pageNumber;
        }
        if ($pageSize = $this->getPageSize()) {
            $query['pageSize'] = $pageSize;
        }
        if ($highlightRegex = $this->getHighlightRegex()) {
            $query['highlightRegex'] = $highlightRegex;
            if ($highlightLimit = $this->getHighlightLimit()) {
                $query['highlightLimit'] = $highlightLimit;
            }
        }
        if ($sessionToken = $this->getSessionToken()) {
            $query['sessionToken'] = $sessionToken;
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    protected function getRequestMultipartStreamElements(): array
    {
        $parts = [];

        $jsonEncoder = $this->getJsonEncoder();
        if ($languages = $this->getLanguages()) {
            $parts['languages'] = $jsonEncoder->encode($languages, 'json');
        }
        if ($query = $this->getQuery()) {
            $parts['query'] = $jsonEncoder->encode($query, 'json');
        }
        if ($sort = $this->getSort()) {
            $parts['sort'] = $jsonEncoder->encode($sort, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setText(?string $text): SearchInterface
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getText(): ?string
    {
        // The special case '***' means 'Give me all the results'.
        return $this->text ?? '***';
    }

    /**
     * @inheritDoc
     */
    public function setLanguages(?array $languages): SearchInterface
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    /**
     * @inheritDoc
     */
    public function setQuery(?array $query): SearchInterface
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): ?array
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function setSort(?array $sort): SearchInterface
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * @inheritDoc
     */
    public function setPageNumber(?int $pageNumber): SearchInterface
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    /**
     * @inheritDoc
     */
    public function setPageSize(?int $pageSize): SearchInterface
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    /**
     * @inheritDoc
     */
    public function setHighlightRegex(?string $highlightRegex): SearchInterface
    {
        $this->highlightRegex = $highlightRegex;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHighlightRegex(): ?string
    {
        return $this->highlightRegex;
    }

    /**
     * @inheritDoc
     */
    public function setHighlightLimit(?int $highlightLimit): SearchInterface
    {
        $this->highlightLimit = $highlightLimit;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHighlightLimit(): ?int
    {
        return $this->highlightLimit;
    }

    /**
     * @inheritDoc
     */
    public function setSessionToken(?string $sessionToken): SearchInterface
    {
        $this->sessionToken = $sessionToken;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSessionToken(): ?string
    {
        return $this->sessionToken;
    }
}


//    public function search1(?string $text = null, array $parameters = []): SearchResult
//    {
//        $parameters = $this->getOptionResolver()
//            ->setRequired('text')
//            ->setAllowedTypes('text', ['string'])
//            ->setDefault('text', '***')
//            ->resolve($parameters);
//
//        $queryKeys = array_flip(['apiKey', 'text']);
//        $queryParameters = array_intersect_key($parameters, $queryKeys);
//        $bodyParameters = array_diff_key($parameters, $queryKeys);
//        $response = $this->send('POST', 'rest/search', $queryParameters, $bodyParameters, true);
//
//        /** @var SearchResult $search */
//        $search = $this->getSerializer()->deserialize((string)$response->getBody(), SearchResult::class, 'json');
//
//        return $search;
//    }
//
//    /**
//     * @inheritDoc
//     */
//    protected function getOptionResolver(): Options
//    {
//        $resolver = parent::getOptionResolver();
//
//        $resolver->setRequired('apiKey')
//            ->setAllowedTypes('apiKey', 'string')
//            ->setDefault('apiKey', $this->client->getConfiguration('apiKey'));
//
//        return $resolver;
//    }
//
//    /**
//     * @inheritDoc
//     */
//    protected function prepareUri(string $path, array $queryParameters = []): string
//    {
//        $base_path = $this->client->getConfiguration(self::SERVER_URL);
//        $uri = rtrim($base_path, '/') . '/' . ltrim($path, '/');
//
//        return $this->addQueryParameters($uri, $queryParameters);
//    }
///**
// * @inheritDoc
// */
//protected function prepareUri(
//    string $path,
//    array $queryParameters = []
//): string {
//    // TODO: Implement prepareUri() method.
//}
