<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\SearchQuery.
 */

namespace EC\EuropaSearch\Search;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SearchQuery
 *
 * @package EC\EuropaSearch\Search
 */
class SearchQuery
{
    /**
     * Full-text search keywords to search.
     *
     * @var string
     */
    private $fullTextKeywords;

    /**
     * Filters to apply on search results.
     *
     * @var array
     */
    private $filters;

    /**
     * Sort criteria for the search.
     *
     * @var array
     */
    private $resultsSorting;

    /**
     * The range of results to return from services.
     *
     * @var array
     */
    private $resultsRange;

    /**
     * Gets the full-text search keywords.
     * @return string
     *   The full-text search keywords.
     */
    public function getFullTextKeywords()
    {
        return $this->fullTextKeywords;
    }

    /**
     * Gets the full-text search keywords.
     * @param string $fullTextKeywords
     *   The full-text search keywords.
     */
    public function setFullTextKeywords($fullTextKeywords)
    {
        $this->fullTextKeywords = $fullTextKeywords;
    }

    /**
     * Gets the search filter definitions.
     * @return array
     *  Array of FilterCriteria object containing filter definitions.
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Sets the search filter definitions.
     * @param array $filters
     *  Array of FilterCriteria object containing filter definitions.
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Gets the search sort criteria
     * @return array
     *   Array with sort settings:
     *   - 'metadata': Metadata on which the sort is based.
     *   - 'direction': The sort direction (ASC or DESC).
     */
    public function getResultsSorting()
    {
        return $this->resultsSorting;
    }

    /**
     * Sets the search sort criteria
     * @param array $resultsSorting
     *   Array with sort settings:
     *   - 'metadata': Metadata on which the sort is based.
     *   - 'direction': The sort direction (ASC or DESC).
     */
    public function setResultsSorting($resultsSorting)
    {
        $this->resultsSorting = $resultsSorting;
    }

    /**
     * Gets the range of results to return from services
     * @return array $resultsRange
     *   Array representing the range settings:
     *   - 'from': int defining the downer boundary of the range;
     *   - 'to': int defining the upper boundary of the range.
     */
    public function getResultsRange()
    {
        return $this->resultsRange;
    }

    /**
     * Sets the range of results to return from services
     * @param array $resultsRange
     *   Array representing the range settings:
     *   - 'from': int defining the downer boundary of the range;
     *   - 'to': int defining the upper boundary of the range.
     */
    public function setResultsRange($resultsRange)
    {
        $this->resultsRange = $resultsRange;
    }



    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('fullTextKeywords', [
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ]);
    }
}
