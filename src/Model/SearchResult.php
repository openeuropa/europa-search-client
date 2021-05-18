<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a search data transfer object.
 */
class SearchResult
{
    /**
     * The API version.
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * A list of best bets documents.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\Document[]
     */
    protected $bestBets;

    /**
     * The field used to group the results.
     *
     * @var string|null
     */
    protected $groupByField;

    /**
     * The page number.
     *
     * @var int
     */
    protected $pageNumber;

    /**
     * The page size.
     *
     * @var int
     */
    protected $pageSize;

    /**
     * The query language.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\QueryLanguage
     */
    protected $queryLanguage;

    /**
     * The response time.
     *
     * @var int
     */
    protected $responseTime;

    /**
     * An array of documents.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\Document[]
     */
    protected $results;

    /**
     * The field and sort type used for sorting.
     *
     * @var string
     */
    protected $sort;

    /**
     * The spelling suggestion.
     *
     * @var string|null
     */
    protected $spellingSuggestion;

    /**
     * The search terms.
     *
     * @var string
     */
    protected $terms;

    /**
     * The total number of results.
     *
     * @var int
     */
    protected $totalResults;

    /**
     * A list of warnings.
     *
     * @var string[]
     */
    protected $warnings;

    /**
     * Returns the API version.
     *
     * @return string
     *   The API version.
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Sets the API version.
     *
     * @param string $apiVersion
     *   The API version.
     * @return $this
     */
    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * Returns the list of best bets documents.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Document[]
     *   The best bets documents.
     */
    public function getBestBets(): array
    {
        return $this->bestBets;
    }

    /**
     * Sets the list of best bets documents.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\Document[] $bestBets
     *   The best bets documents.
     * @return $this
     */
    public function setBestBets(array $bestBets): self
    {
        $this->bestBets = $bestBets;
        return $this;
    }

    /**
     * Returns the field used to group the results.
     *
     * @return string|null
     *   The field used to group the results.
     */
    public function getGroupByField(): ?string
    {
        return $this->groupByField;
    }

    /**
     * Sets the field used to group the results.
     *
     * @param string|null $groupByField
     *   The field used to group the results.
     * @return $this
     */
    public function setGroupByField(?string $groupByField): self
    {
        $this->groupByField = $groupByField;
        return $this;
    }

    /**
     * Returns the page number.
     *
     * @return int
     *   The page number.
     */
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    /**
     * Sets the page number.
     *
     * @param int $pageNumber
     *   The page number.
     * @return $this
     */
    public function setPageNumber(int $pageNumber): self
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * Returns the page size.
     *
     * @return int
     *   The page size.
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Sets the page size.
     *
     * @param int $pageSize
     *   The page size.
     * @return $this
     */
    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * Returns the query language.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\QueryLanguage
     *   The query language.
     */
    public function getQueryLanguage(): QueryLanguage
    {
        return $this->queryLanguage;
    }

    /**
     * Sets the query language.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\QueryLanguage $queryLanguage
     *   The query language.
     * @return $this
     */
    public function setQueryLanguage(QueryLanguage $queryLanguage): self
    {
        $this->queryLanguage = $queryLanguage;
        return $this;
    }

    /**
     * Returns the response time.
     *
     * @return int
     *   The response time.
     */
    public function getResponseTime(): int
    {
        return $this->responseTime;
    }

    /**
     * Sets the response time.
     *
     * @param int $responseTime
     *   The response time.
     * @return $this
     */
    public function setResponseTime(int $responseTime): self
    {
        $this->responseTime = $responseTime;
        return $this;
    }

    /**
     * Returns the list of result documents.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Document[]
     *   An array of documents.
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Sets the list of result documents.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\Document[] $results
     *   An array of documents.
     * @return $this
     */
    public function setResults(array $results): self
    {
        $this->results = $results;
        return $this;
    }

    /**
     * Returns the sort used for the search.
     *
     * @return string
     *   The field and sort type used for sorting.
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * Sets the search sort.
     *
     * @param string $sort
     *   The field and sort type used for sorting, separated by a colon.
     * @return $this
     */
    public function setSort(string $sort): self
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Returns the spelling suggestion.
     *
     * @return string|null
     *   The spelling suggestion.
     */
    public function getSpellingSuggestion(): ?string
    {
        return $this->spellingSuggestion;
    }

    /**
     * Sets the spelling suggestion.
     *
     * @param string|null $spellingSuggestion
     *   The spelling suggestion.
     * @return $this
     */
    public function setSpellingSuggestion(?string $spellingSuggestion): self
    {
        $this->spellingSuggestion = $spellingSuggestion;
        return $this;
    }

    /**
     * Returns the search terms.
     *
     * @return string
     *   The search terms.
     */
    public function getTerms(): string
    {
        return $this->terms;
    }

    /**
     * Sets the search terms.
     *
     * @param string $terms
     *   The search terms.
     * @return $this
     */
    public function setTerms(string $terms): self
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * Returns the total number of results.
     *
     * @return int
     *   The total number of results.
     */
    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    /**
     * Sets the total number of results.
     *
     * @param int $totalResults
     *   The total number of results.
     * @return $this
     */
    public function setTotalResults(int $totalResults): self
    {
        $this->totalResults = $totalResults;
        return $this;
    }

    /**
     * Returns the list of warnings.
     *
     * @return string[]
     *   An array of strings representing warnings.
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Sets the warning list.
     *
     * @param string[] $warnings
     *   An array of strings representing warnings.
     * @return $this
     */
    public function setWarnings(array $warnings): self
    {
        $this->warnings = $warnings;
        return $this;
    }
}
