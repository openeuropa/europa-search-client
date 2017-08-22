<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Combined\SimpleFilterDataProvider.
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\BooleanQuery;

/**
 * Class BooleanQueryDataProvider.
 *
 * Supplies a set of dataProvider containing BooleanQuery for
 * CombinedQuery tests.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Combined
 */
class BooleanQueryDataProvider
{

    /**
     * Gets a valid BooleanQuery object with a nested BooleanQuery object.
     *
     * @return BooleanQuery
     *  The valid BooleanQuery object.
     */
    public function getValidNestedBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add simple Filters.
        $filters = $filterProvider->getValidFilters();
        foreach ($filters as $filter) {
            $booleanQuery->addMustSimpleFilter($filter);
            $booleanQuery->addMustNotSimpleFilter($filter);
            $booleanQuery->addShouldSimpleFilter($filter);
        }

        $nestedBooleanQuery = $this->getValidSimpleBooleanQuery();
        $booleanQuery->addMustCombinedFilter($nestedBooleanQuery);
        $booleanQuery->addMustNotCombinedFilter($nestedBooleanQuery);
        $booleanQuery->addShouldCombinedFilter($nestedBooleanQuery);

        $nestedBoostingQuery = $boostingProvider->getValidBoostingQuery();
        $booleanQuery->addShouldCombinedFilter($nestedBoostingQuery);

        return $booleanQuery;
    }

    /**
     * Gets a nested must invalid BooleanQuery object.
     *
     * @return BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustInvalidNestedBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add valid filters.
        $validBoostingQuery = $boostingProvider->getValidBoostingQuery();
        $validFilters = $filterProvider->getValidFilters();
        $validBooleanQuery = $this->getValidSimpleBooleanQuery();

        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustNotSimpleFilter($validFilter);
            $booleanQuery->addShouldSimpleFilter($validFilter);
        }
        $booleanQuery->addMustNotCombinedFilter($validBooleanQuery);
        $booleanQuery->addShouldCombinedFilter($validBoostingQuery);

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        $inValidBooleanQuery = $this->getMustInValidSimpleBooleanQuery();

        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustSimpleFilter($invalidFilter);
        }
        $booleanQuery->addMustCombinedFilter($inValidBooleanQuery);

        return $booleanQuery;
    }

    /**
     * Gets a nested mustNot invalid BooleanQuery object.
     *
     * @return BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustNotInvalidNestedBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        $validBoostingQuery = $boostingProvider->getValidBoostingQuery();
        $validBooleanQuery = $this->getValidSimpleBooleanQuery();

        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustSimpleFilter($validFilter);
            $booleanQuery->addShouldSimpleFilter($validFilter);
        }
        $booleanQuery->addMustCombinedFilter($validBooleanQuery);
        $booleanQuery->addShouldCombinedFilter($validBoostingQuery);

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        $inValidBooleanQuery = $this->getMustInValidSimpleBooleanQuery();

        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustNotSimpleFilter($invalidFilter);
        }
        $booleanQuery->addMustNotCombinedFilter($inValidBooleanQuery);

        return $booleanQuery;
    }

    /**
     * Gets a nested should invalid BooleanQuery object.
     *
     * @return BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getShouldInvalidNestedBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        $validBooleanQuery = $this->getValidSimpleBooleanQuery();

        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustSimpleFilter($validFilter);
            $booleanQuery->addMustNotSimpleFilter($validFilter);
        }
        $booleanQuery->addMustNotCombinedFilter($validBooleanQuery);
        $booleanQuery->addMustCombinedFilter($validBooleanQuery);

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        $inValidBooleanQuery = $this->getMustInValidSimpleBooleanQuery();

        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addShouldSimpleFilter($invalidFilter);
        }
        $booleanQuery->addShouldCombinedFilter($inValidBooleanQuery);

        $inValidBoostingQuery = $boostingProvider->getPositiveInValidBoostingQuery();
        $booleanQuery->addShouldCombinedFilter($inValidBoostingQuery);

        return $booleanQuery;
    }

    /**
     * Gets a valid BooleanQuery object.
     *
     * @return BooleanQuery
     *  The valid BooleanQuery object.
     */
    public function getValidSimpleBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();

        // Add  simple Filters.
        $filters = $filterProvider->getValidFilters();
        foreach ($filters as $filter) {
            $booleanQuery->addMustSimpleFilter($filter);
            $booleanQuery->addMustNotSimpleFilter($filter);
            $booleanQuery->addShouldSimpleFilter($filter);
        }

        return $booleanQuery;
    }

    /**
     * Gets a must invalid BooleanQuery object.
     *
     * @return BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustInvalidSimpleBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustNotSimpleFilter($validFilter);
            $booleanQuery->addShouldSimpleFilter($validFilter);
        }

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustSimpleFilter($invalidFilter);
        }

        return $booleanQuery;
    }

    /**
     * Gets a mustNot invalid BooleanQuery object.
     *
     * @return BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getMustNotInvalidSimpleBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustSimpleFilter($validFilter);
            $booleanQuery->addShouldSimpleFilter($validFilter);
        }

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addMustNotSimpleFilter($invalidFilter);
        }

        return $booleanQuery;
    }

    /**
     * Gets a should invalid BooleanQuery object.
     *
     * @return BooleanQuery
     *  The invalid BooleanQuery object.
     */
    public function getShouldInvalidSimpleBooleanQuery()
    {

        $booleanQuery = new BooleanQuery();

        $filterProvider = new SimpleFilterDataProvider();

        // Add valid filters.
        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $booleanQuery->addMustSimpleFilter($validFilter);
            $booleanQuery->addMustNotSimpleFilter($validFilter);
        }

        // Add invalid filters.
        $invalidFilters = $filterProvider->getInValidFilters();
        foreach ($invalidFilters as $invalidFilter) {
            $booleanQuery->addShouldSimpleFilter($invalidFilter);
        }

        return $booleanQuery;
    }
}
