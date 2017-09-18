<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Queries\BooleanQuery.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\BoostableFilter;
use EC\EuropaSearch\Messages\Search\Filters\Clauses\AbstractClause;
use EC\EuropaWS\Proxies\BasicProxyController;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BooleanQuery.
 *
 * Represents a Boolean query compound of Europa Search; I.E:
 * "An object allowing combining multiple filter type and other compounds."
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Combined
 */
class BooleanQuery extends BoostableFilter implements FilterQueryInterface
{
    /**
     * The list of filters that MUST fulfil the search items.
     *
     * @var array
     */
    private $mustFilterList;

    /**
     * The list of filters that SHOULD fulfil the search items.
     *
     * @var array
     */
    private $shouldFilterList;

    /**
     * The list of filters that MUST NOT fulfil the search items.
     *
     * @var array
     */
    private $mustNotFilterList;

    /**
     * BooleanQuery constructor.
     */
    public function __construct()
    {

        $this->mustFilterList = new FilterQueryComponent('must');
        $this->shouldFilterList = new FilterQueryComponent('should');
        $this->mustNotFilterList = new FilterQueryComponent('must_not');
    }

    /**
     * Gets the list of filters that MUST fulfil the search items.
     *
     * @return AggregatedFilters
     *   The list of filters.
     */
    public function getMustFilterList()
    {
        return $this->mustFilterList;
    }

    /**
     * Gets the list of filters that SHOULD fulfil the search items.
     *
     * @return AggregatedFilters
     *   The list of filters.
     */
    public function getShouldFilterList()
    {
        return $this->shouldFilterList;
    }

    /**
     * Gets the list of filters that MUST NOT fulfil the search items.
     *
     * @return AggregatedFilters
     *   The list of filters.
     */
    public function getMustNotFilterList()
    {
        return $this->mustNotFilterList;
    }

    /**
     * Add a filter clause to the filters that MUST fulfil the search items.
     *
     * @param AbstractClause $filterClause
     *   The filter to add
     */
    public function addMustFilterClause(AbstractClause $filterClause)
    {
        $this->mustFilterList->addFilterClause($filterClause);
    }

    /**
     * Add a filter clause to the filters that SHOULD fulfil the search items.
     *
     * @param AbstractClause $filterClause
     *   The filter to add
     */
    public function addShouldFilterClause(AbstractClause $filterClause)
    {
        $this->shouldFilterList->addFilterClause($filterClause);
    }

    /**
     * Add a filter clause to the filters that MUST NOT fulfil the search items.
     *
     * @param AbstractClause $filterClause
     *   The filter to add
     */
    public function addMustNotFilterClause(AbstractClause $filterClause)
    {
        $this->mustNotFilterList->addFilterClause($filterClause);
    }

    /**
     * Add a simple filter to the filters that MUST fulfil the search items.
     *
     * @param FilterQueryInterface $filterQuery
     *   The filter to add
     */
    public function addMustFilterQuery(FilterQueryInterface $filterQuery)
    {
        $this->mustFilterList->addFilterQuery($filterQuery);
    }

    /**
     * Add a simple filter to the filters that SHOULD fulfil the search items.
     *
     * @param FilterQueryInterface $filterQuery
     *   The filter to add
     */
    public function addShouldFilterQuery(FilterQueryInterface $filterQuery)
    {
        $this->shouldFilterList->addFilterQuery($filterQuery);
    }

    /**
     * Add a simple filter to the filters that MUST NOT fulfil the search items.
     *
     * @param FilterQueryInterface $filterQuery
     *   The filter to add
     */
    public function addMustNotFilterQuery(FilterQueryInterface $filterQuery)
    {
        $this->mustNotFilterList->addFilterQuery($filterQuery);
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return BasicProxyController::COMPONENT_ID_PREFIX.'searching.filters.queries.booleanQuery';
    }

    /**
     * {@inheritDoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('mustFilterList', new Assert\Valid());
        $metadata->addPropertyConstraint('mustNotFilterList', new Assert\Valid());
        $metadata->addPropertyConstraint('shouldFilterList', new Assert\Valid());
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for BooleanQuery.
     *
     * @param ExecutionContextInterface $context
     * @param int                       $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

        if (empty($this->getMustFilterList()) && empty($this->getMustNotFilterList()) && empty($this->getShouldFilterList())) {
            $context->buildViolation('At least one of the filter list must filled.')
                ->atPath('mustFilterList')
                ->addViolation();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getChildComponents()
    {
        return [
            $this->mustFilterList,
            $this->mustNotFilterList,
            $this->shouldFilterList,
        ];
    }
}
