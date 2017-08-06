<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\GroupedClause.
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

/**
 * Class GroupedClause.
 *
 * It represents a list of clauses to use as an "AND" combination of clause.
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
class GroupedClause extends AbstractClause
{
    private $group = array();

    /**
     * The list of clauses implied in the "AND" combination of clause.
     * @return array
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Adds a clause to the group.
     *
     * @param ClauseInterface $clause
     *   The clause to add
     */
    public function addClause(ClauseInterface $clause)
    {
        $key = $clause->getMetadataName();
        $this->group[$key] = $clause;
    }
}
