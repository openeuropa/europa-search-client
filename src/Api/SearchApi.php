<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\SearchApiInterface;
use OpenEuropa\EuropaSearchClient\Model\Search;
use Psr\Http\Message\UriInterface;

/**
 * Search API.
 */
class SearchApi extends SearchApiBase implements SearchApiInterface
{
    /**
     * @var array
     * @todo Create a Sort model in OEL-173
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-173
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
     * @inheritDoc
     */
    public function search(): Search
    {
        /** @var Search $search */
        $search = $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            Search::class,
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
    protected function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($sort = $this->getSort()) {
            $parts['sort']['content'] = $this->jsonEncoder->encode($sort, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setSort(?array $sort): SearchApiInterface
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
    public function setPageNumber(?int $pageNumber): SearchApiInterface
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
    public function setPageSize(?int $pageSize): SearchApiInterface
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
    public function setHighlightRegex(?string $highlightRegex): SearchApiInterface
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
    public function setHighlightLimit(?int $highlightLimit): SearchApiInterface
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
