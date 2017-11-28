<?php

namespace EC\EuropaSearch\Tests\Messages\Components\Filters\Queries;

use EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery;
use EC\EuropaSearch\Tests\Messages\Components\Filters\Clauses\FilterClauseDataProvider;

/**
 * Class BooleanQueryDataProvider.
 *
 * Supplies a set of dataProvider containing BooleanQuery for
 * CombinedQuery tests.
 *
 * @package EC\EuropaSearch\Tests\Messages\Components\Filters\Queries
 */
class BooleanQueryDataProvider
{

    /**
     * Gets a valid BooleanQuery object with a nested BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The valid BooleanQuery object.
     */
    public function getValidNestedBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add simple Filters.
        $filters = $filterProvider->getValidFilters();
        foreach ($filters as $filter) {
            $booleanQuery->addMustFilterClause($filter);
            $booleanQuery->addMustNotFilterClause($filter);
            $booleanQuery->addShouldFilterClause($filter);
        }

        $nestedBooleanQuery = $this->getValidSimpleBooleanQuery();
        $booleanQuery->addMustFilterQuery($nestedBooleanQuery);
        $booleanQuery->addMustNotFilterQuery($nestedBooleanQuery);
        $booleanQuery->addShouldFilterQuery($nestedBooleanQuery);

        $nestedBoostingQuery = $boostingProvider->getValidBoostingQuery();
        $booleanQuery->addShouldFilterQuery($nestedBoostingQuery);

        return $booleanQuery;
    }

    /**
     * Gets a nested must invalid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustInvalidNestedBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add valid filters.
        $validBoostingQuery = $boostingProvider->getValidBoostingQuery();
        $validFilters = $filterProvider->getValidFilters();
        $validBooleanQuery = $this->getValidSimpleBooleanQuery();

        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustNotFilterClause($validFilter);
            $booleanQuery->addShouldFilterClause($validFilter);
        }
        $booleanQuery->addMustNotFilterQuery($validBooleanQuery);
        $booleanQuery->addShouldFilterQuery($validBoostingQuery);

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        $inValidBooleanQuery = $this->getMustInValidSimpleBooleanQuery();

        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustFilterClause($invalidFilter);
        }
        $booleanQuery->addMustFilterQuery($inValidBooleanQuery);

        return $booleanQuery;
    }

    /**
     * Gets a nested mustNot invalid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustNotInvalidNestedBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        $validBoostingQuery = $boostingProvider->getValidBoostingQuery();
        $validBooleanQuery = $this->getValidSimpleBooleanQuery();

        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustFilterClause($validFilter);
            $booleanQuery->addShouldFilterClause($validFilter);
        }
        $booleanQuery->addMustFilterQuery($validBooleanQuery);
        $booleanQuery->addShouldFilterQuery($validBoostingQuery);

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        $inValidBooleanQuery = $this->getMustInValidSimpleBooleanQuery();

        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustNotFilterClause($invalidFilter);
        }
        $booleanQuery->addMustNotFilterQuery($inValidBooleanQuery);

        return $booleanQuery;
    }

    /**
     * Gets a nested should invalid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getShouldInvalidNestedBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        $validBooleanQuery = $this->getValidSimpleBooleanQuery();

        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustFilterClause($validFilter);
            $booleanQuery->addMustNotFilterClause($validFilter);
        }
        $booleanQuery->addMustNotFilterQuery($validBooleanQuery);
        $booleanQuery->addMustFilterQuery($validBooleanQuery);

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        $inValidBooleanQuery = $this->getMustInValidSimpleBooleanQuery();

        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addShouldFilterClause($invalidFilter);
        }
        $booleanQuery->addShouldFilterQuery($inValidBooleanQuery);

        $inValidBoostingQuery = $boostingProvider->getPositiveInValidBoostingQuery();
        $booleanQuery->addShouldFilterQuery($inValidBoostingQuery);

        return $booleanQuery;
    }

    /**
     * Gets a valid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The valid BooleanQuery object.
     */
    public function getValidSimpleBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();

        // Add  simple Filters.
        $filters = $filterProvider->getValidFilters();
        foreach ($filters as $filter) {
            $booleanQuery->addMustFilterClause($filter);
            $booleanQuery->addMustNotFilterClause($filter);
            $booleanQuery->addShouldFilterClause($filter);
        }

        return $booleanQuery;
    }

    /**
     * Gets a must invalid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustInvalidSimpleBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustNotFilterClause($validFilter);
            $booleanQuery->addShouldFilterClause($validFilter);
        }

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustFilterClause($invalidFilter);
        }

        return $booleanQuery;
    }

    /**
     * Gets a mustNot invalid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustNotInvalidSimpleBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustFilterClause($validFilter);
            $booleanQuery->addShouldFilterClause($validFilter);
        }

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustNotFilterClause($invalidFilter);
        }

        return $booleanQuery;
    }

    /**
     * Gets a should invalid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getShouldInvalidSimpleBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustFilterClause($validFilter);
            $booleanQuery->addMustNotFilterClause($validFilter);
        }

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addShouldFilterClause($invalidFilter);
        }

        return $booleanQuery;
    }

    /**
     * Gets a should invalid BooleanQuery object.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getTwoLevelBooleanQuery()
    {
        $booleanQuery = new BooleanQuery();

        $filterProvider = new FilterClauseDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustFilterClause($validFilter);
            $booleanQuery->addMustNotFilterClause($validFilter);
        }

        $booleanQuery->addShouldFilterQuery($this->getValidSimpleBooleanQuery());

        return $booleanQuery;
    }
}
