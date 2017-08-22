<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Combined\SimpleFilterDataProvider.
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\BoostingQuery;

/**
 * Class BoostingQueryDataProvider.
 *
 * Supplies a set of dataProvider containing BoostingQuery for
 * CombinedQuery tests.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Combined
 */
class BoostingQueryDataProvider
{

    /**
     * Gets valid BoostingQuery object for testing.
     *
     * @return BoostingQuery
     *   The valid BoostingQuery objects.
     */
    public function getValidBoostingQuery()
    {

        $boostingQuery = new BoostingQuery();

        $simpleFilterProvider = new SimpleFilterDataProvider();

        // Add Term list.
        $filters = $simpleFilterProvider->getValidTermList();
        foreach ($filters as $filter) {
            $boostingQuery->addValueInPositiveFilters($filter);
            $boostingQuery->addValueInNegativeFilters($filter);
        }

        // Add Terms list.
        $filters = $simpleFilterProvider->getValidTermsList();
        foreach ($filters as $filter) {
            $boostingQuery->addValuesInPositiveFilters($filter);
            $boostingQuery->addValuesInNegativeFilters($filter);
        }

        return $boostingQuery;
    }

    /**
     * Gets positive invalid BoostingQuery object for testing.
     *
     * @return BoostingQuery
     *   The invalid BoostingQuery objects.
     */
    public function getPositiveInValidBoostingQuery()
    {

        $boostingQuery = new BoostingQuery();

        $simpleFilterProvider = new SimpleFilterDataProvider();

        // Add valid filters.
        $validTermList = $simpleFilterProvider->getValidTermList();
        foreach ($validTermList as $filter) {
            $boostingQuery->addValueInNegativeFilters($filter);
        }

        $validTermsList = $simpleFilterProvider->getValidTermsList();
        foreach ($validTermsList as $filter) {
            $boostingQuery->addValuesInNegativeFilters($filter);
        }

        // Add invalid filters.
        $inValidTermList = $simpleFilterProvider->getInValidTermList();
        foreach ($inValidTermList as $filter) {
            $boostingQuery->addValueInPositiveFilters($filter);
        }

        $inValidTermsList = $simpleFilterProvider->getInValidTermsList();
        foreach ($inValidTermsList as $filter) {
            $boostingQuery->addValuesInPositiveFilters($filter);
        }

        return $boostingQuery;
    }

    /**
     * Gets negative invalid BoostingQuery object for testing.
     *
     * @return BoostingQuery
     *   The invalid BoostingQuery objects.
     */
    public function getNegativeInValidBoostingQuery()
    {

        $boostingQuery = new BoostingQuery();

        $simpleFilterProvider = new SimpleFilterDataProvider();

        // Add valid filters.
        $validTermList = $simpleFilterProvider->getValidTermList();
        foreach ($validTermList as $filter) {
            $boostingQuery->addValueInPositiveFilters($filter);
        }

        $validTermsList = $simpleFilterProvider->getValidTermsList();
        foreach ($validTermsList as $filter) {
            $boostingQuery->addValuesInPositiveFilters($filter);
        }

        // Add invalid filters.
        $inValidTermList = $simpleFilterProvider->getInValidTermList();
        foreach ($inValidTermList as $filter) {
            $boostingQuery->addValueInNegativeFilters($filter);
        }

        $inValidTermsList = $simpleFilterProvider->getInValidTermsList();
        foreach ($inValidTermsList as $filter) {
            $boostingQuery->addValuesInNegativeFilters($filter);
        }

        return $boostingQuery;
    }
}
