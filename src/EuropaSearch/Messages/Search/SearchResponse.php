<?php

namespace EC\EuropaSearch\Messages\Search;

use EC\EuropaSearch\Messages\MessageInterface;

/**
 * Class SearchResponse.
 *
 * It represents a response coming from the search web service.
 *
 * @package EC\EuropaSearch\Messages\Search
 */
class SearchResponse implements MessageInterface
{

    const SORT_CRITERIA_RELEVANCE = 'relevance';

    /**
     * Searched terms.
     *
     * @var string
     */
    protected $searchedTerms;

    /**
     * The total of found results.
     *
     * @var int
     */
    protected $totalResults;

    /**
     * The number of results pages.
     *
     * @var int
     */
    protected $pageNumber;

    /**
     * The number of results per page.
     *
     * @var int
     */
    protected $pageSize;

    /**
     * The sort criteria used in the search.
     *
     * @var string
     */
    protected $resultSorting;

    /**
     * The language used with the search.
     *
     * @var string
     */
    protected $language;

    /**
     * The validity probability of the detected search language.
     *
     * @var float
     */
    protected $languageProbability;

    /**
     * List of results.
     *
     * @var array
     */
    protected $results = [];

    /**
     * Gets the searched terms.
     *
     * @return string
     *   The concatenated searched terms.
     */
    public function getSearchedTerms()
    {
        return $this->searchedTerms;
    }

    /**
     * Sets the searched terms.
     *
     * @param string $searchedTerms
     *   The concatenated searched terms.
     */
    public function setSearchedTerms($searchedTerms)
    {
        $this->searchedTerms = $searchedTerms;
    }

    /**
     * Gets the total of found results.
     *
     * @return int
     *   The total number.
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    /**
     * Sets the total of found results.
     *
     * @param int $totalResults
     *   The total number.
     */
    public function setTotalResults($totalResults)
    {
        $this->totalResults = $totalResults;
    }

    /**
     * Gets the number of results pages.
     *
     * @return int
     *   The number of pages.
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Sets the number of results pages.
     *
     * @param int $pageNumber
     *   The number of pages.
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }

    /**
     * Gets the number of results per page.
     *
     * @return int
     *   The number of results per page.
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * Sets the number of results per page.
     *
     * @param int $pageSize
     *   The number of results per page.
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Gets the sort criteria used in the search.
     *
     * @return string
     *   The sort criteria.
     */
    public function getResultSorting()
    {
        return $this->resultSorting;
    }

    /**
     * Sets the sort criteria used in the search.
     *
     * @param string $resultSorting
     *   The sort criteria.
     */
    public function setResultSorting($resultSorting)
    {
        $this->resultSorting = $resultSorting;
    }

    /**
     * Gets the language used with the search.
     *
     * @return string
     *   The language code.
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the language used with the search.
     *
     * @param string $language
     *   The language code to set.
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Gets the validity probability of the detected search language.
     *
     * @return float
     *   The returned probability.
     */
    public function getLanguageProbability()
    {
        return $this->languageProbability;
    }

    /**
     * Sets the validity probability of the detected search language.
     *
     * @param float $languageProbability
     *   The returned probability.
     */
    public function setLanguageProbability($languageProbability)
    {
        $this->languageProbability = $languageProbability;
    }

    /**
     * Gets the list of results objects of the search.
     *
     * @return array
     *   An array of SearchResult objects.
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Sets the list of results objects of the search.
     *
     * @param array $results
     *   An array of SearchResult objects.
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     * Adds a result to the results list of the object.
     *
     * @param SearchResult $result
     *   The search result to add.
     */
    public function addResult(SearchResult $result)
    {
        $this->results[] = $result;
    }
}
