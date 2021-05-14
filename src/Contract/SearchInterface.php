<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\SearchResult;

interface SearchInterface extends ApiInterface
{
    /**
     * @return SearchResult
     */
    public function search(): SearchResult;

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText(?string $text): self;

    /**
     * @return string|null
     */
    public function getText(): ?string;

    /**
     * @param array|null $languages
     *
     * @return $this
     */
    public function setLanguages(?array $languages): self;

    /**
     * @return array|null
     */
    public function getLanguages(): ?array;

    /**
     * @param array|null $query
     *
     * @return $this
     */
    public function setQuery(?array $query): self;

    /**
     * @return array|null
     */
    public function getQuery(): ?array;

    /**
     * @param array|null $sort
     *
     * @return $this
     */
    public function setSort(?array $sort): self;

    /**
     * @return array|null
     */
    public function getSort(): ?array;

    /**
     * @param int|null $pageNumber
     *
     * @return $this
     */
    public function setPageNumber(?int $pageNumber): self;

    /**
     * @return int|null
     */
    public function getPageNumber(): ?int;

    /**
     * @param int|null $pageSize
     *
     * @return $this
     */
    public function setPageSize(?int $pageSize): self;

    /**
     * @return int|null
     */
    public function getPageSize(): ?int;

    /**
     * @param string|null $highlightRegex
     *
     * @return $this
     */
    public function setHighlightRegex(?string $highlightRegex): self;

    /**
     * @return string|null
     */
    public function getHighlightRegex(): ?string;

    /**
     * @param int|null $highlightLimit
     *
     * @return $this
     */
    public function setHighlightLimit(?int $highlightLimit): self;

    /**
     * @return int|null
     */
    public function getHighlightLimit(): ?int;

    /**
     * @param string|null $sessionToken
     *
     * @return $this
     */
    public function setSessionToken(?string $sessionToken): self;

    /**
     * @return string|null
     */
    public function getSessionToken(): ?string;
}
