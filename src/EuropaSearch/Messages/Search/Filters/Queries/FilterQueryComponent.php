<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Queries\FilterQueryComponent.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Clauses\AbstractClause;
use EC\EuropaWS\Proxies\BasicProxyController;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FilterQueryComponent.
 *
 * Object representing a aggregation of filters.
 *
 * It can be made of objects of:
 * - The EC\EuropaSearch\Messages\Search\Filters\Clauses package;
 * - The EC\EuropaSearch\Messages\Search\Filters\Queries\BooleanQuery type;
 * - The EC\EuropaSearch\Messages\Search\Filters\Queries\BoostingQuery type;
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Queries
 */
class FilterQueryComponent implements FilterQueryInterface
{

    /**
     * Label to identify the aggregation.
     *
     * @var string
     */
    private $aggregationLabel;

    /**
     * The list of aggregated filters.
     *
     * @var array
     */
    private $filterList;

    /**
     * AggregatedFilters constructor.
     *
     * @param string $aggregationLabel
     *   The label to identify the aggregation.
     */
    public function __construct($aggregationLabel)
    {
        $this->aggregationLabel = $aggregationLabel;
        $this->filterList = [];
    }

    /**
     * Gets the label to identify the aggregation..
     *
     * @return string
     *   The label to identify the aggregation.
     */
    public function getAggregationLabel()
    {
        return $this->aggregationLabel;
    }

    /**
     * Gets the list of aggregate filters.
     *
     * @return array
     *   Array made of AbstractSimple filters or CombinedQueryInterface
     *   implementation (BooleanQuery or BoostingQuery objects).
     */
    public function getFilterList()
    {
        return $this->filterList;
    }

    /**
     * Adds an AbstractClause to the aggregated filters;
     *
     * @param AbstractClause $filterClause
     *   The AbstractSimple $filter to add
     */
    public function addFilterClause(AbstractClause $filterClause)
    {
        $this->filterList[] = $filterClause;
    }

    /**
     * Adds an FilterQueryInterface query to the aggregated filters;
     *
     * @param FilterQueryInterface $filterQuery
     *   The CombinedQueryInterface query to add
     */
    public function addFilterQuery(FilterQueryInterface $filterQuery)
    {
        $this->filterList[] = $filterQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return BasicProxyController::COMPONENT_ID_PREFIX.'searching.filters.queries.aggregate';
    }

    /**
     * {@inheritDoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('filterList', new Assert\Valid(['traverse' => true]));
    }

    /**
     * {@inheritDoc}
     */
    public function getChildComponents()
    {
        return $this->filterList;
    }
}
