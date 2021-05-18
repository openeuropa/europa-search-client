<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\SearchInterface;
use OpenEuropa\EuropaSearchClient\Model\SearchResult;
use OpenEuropa\EuropaSearchClient\Traits\LanguagesAwareTrait;
use Psr\Http\Message\UriInterface;

/**
 * Search API.
 */
class Search extends ApiBase implements SearchInterface
{
    use LanguagesAwareTrait;

    /**
     * @var string
     */
    protected $text;

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
        $search = $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            SearchResult::class,
            'json'
        );
        return $search;
    }

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'apiKey' => [
                'type' => 'string',
                'required' => true,
            ],
            'searchApiEndpoint' => $this->getEndpointSchema(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('searchApiEndpoint');
    }

    /**
     * @inheritDoc
     */
    protected function getRequestUriQuery(UriInterface $uri): array
    {
        $query = parent::getRequestUriQuery($uri);

        $query['apiKey'] = $this->getConfigValue('apiKey');
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

        if ($languages = $this->getLanguages()) {
            $parts['languages'] = $this->jsonEncoder->encode($languages, 'json');
        }
        if ($query = $this->getQuery()) {
            $parts['query'] = $this->jsonEncoder->encode($query, 'json');
        }
        if ($sort = $this->getSort()) {
            $parts['sort'] = $this->jsonEncoder->encode($sort, 'json');
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
