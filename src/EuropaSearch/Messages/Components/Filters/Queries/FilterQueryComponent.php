<?php

namespace EC\EuropaSearch\Messages\Components\Filters\Queries;

use EC\EuropaSearch\Messages\Components\NestedComponentInterface;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\AbstractClause;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FilterQueryComponent.
 *
 * Object representing a aggregation of filters.
 *
 * It can be made of objects of:
 * - The EC\EuropaSearch\Messages\Components\Filters\Clauses package;
 * - The EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery type;
 * - The EC\EuropaSearch\Messages\Components\Filters\Queries\BoostingQuery type;
 *
 * @package EC\EuropaSearch\Messages\Components\Filters\Queries
 */
class FilterQueryComponent implements NestedComponentInterface
{

    /**
     * Label to identify the aggregation.
     *
     * @var string
     */
    protected $aggregationLabel;

    /**
     * The list of aggregated filters.
     *
     * @var array
     */
    protected $filterList;

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
     * @param \EC\EuropaSearch\Messages\Components\Filters\Clauses\AbstractClause $filterClause
     *   The AbstractSimple $filter to add
     */
    public function addFilterClause(AbstractClause $filterClause)
    {
        $this->filterList[] = $filterClause;
    }

    /**
     * Adds an FilterQueryInterface query to the aggregated filters;
     *
     * @param \EC\EuropaSearch\Messages\Components\NestedComponentInterface $filterQuery
     *   The CombinedQueryInterface query to add
     */
    public function addFilterQuery(NestedComponentInterface $filterQuery)
    {
        $this->filterList[] = $filterQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return 'europaSearch.componentProxy.searching.filters.queries.aggregate';
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
