<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\SearchInterface;
use OpenEuropa\EuropaSearchClient\Model\SearchResult;
use Psr\Http\Message\UriInterface;

/**
 * Search API.
 */
class Search extends SearchBase implements SearchInterface
{
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
            'searchApiEndpoint' => $this->getEndpointSchema(),
        ] + parent::getConfigSchema();
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

        return $query;
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
}
