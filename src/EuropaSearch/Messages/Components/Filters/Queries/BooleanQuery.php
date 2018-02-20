<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\Filters\Queries;

use OpenEuropa\EuropaSearch\Messages\Components\Filters\BoostableFilter;
use OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses\AbstractClause;
use OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BooleanQuery.
 *
 * Represents a Boolean query compound of Europa Search; I.E:
 * "An object allowing combining multiple filter type and other compounds."
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components\Filters\Combined
 */
class BooleanQuery extends BoostableFilter implements NestedComponentInterface
{
    /**
     * The list of filters that MUST fulfil the search items.
     *
     * @var array
     */
    protected $mustFilterList;

    /**
     * The list of filters that SHOULD fulfil the search items.
     *
     * @var array
     */
    protected $shouldFilterList;

    /**
     * The list of filters that MUST NOT fulfil the search items.
     *
     * @var array
     */
    protected $mustNotFilterList;

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
     * @return array
     *   The list of filters.
     */
    public function getMustFilterList()
    {
        return $this->mustFilterList;
    }

    /**
     * Gets the list of filters that SHOULD fulfil the search items.
     *
     * @return array
     *   The list of filters.
     */
    public function getShouldFilterList()
    {
        return $this->shouldFilterList;
    }

    /**
     * Gets the list of filters that MUST NOT fulfil the search items.
     *
     * @return array
     *   The list of filters.
     */
    public function getMustNotFilterList()
    {
        return $this->mustNotFilterList;
    }

    /**
     * Add a filter clause to the filters that MUST fulfil the search items.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses\AbstractClause $filterClause
     *   The filter to add
     */
    public function addMustFilterClause(AbstractClause $filterClause)
    {
        $this->mustFilterList->addFilterClause($filterClause);
    }

    /**
     * Add a filter clause to the filters that SHOULD fulfil the search items.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses\AbstractClause $filterClause
     *   The filter to add
     */
    public function addShouldFilterClause(AbstractClause $filterClause)
    {
        $this->shouldFilterList->addFilterClause($filterClause);
    }

    /**
     * Add a filter clause to the filters that MUST NOT fulfil the search items.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses\AbstractClause $filterClause
     *   The filter to add
     */
    public function addMustNotFilterClause(AbstractClause $filterClause)
    {
        $this->mustNotFilterList->addFilterClause($filterClause);
    }

    /**
     * Add a simple filter to the filters that MUST fulfil the search items.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface $filterQuery
     *   The filter to add
     */
    public function addMustFilterQuery(NestedComponentInterface $filterQuery)
    {
        $this->mustFilterList->addFilterQuery($filterQuery);
    }

    /**
     * Add a simple filter to the filters that SHOULD fulfil the search items.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface $filterQuery
     *   The filter to add
     */
    public function addShouldFilterQuery(NestedComponentInterface $filterQuery)
    {
        $this->shouldFilterList->addFilterQuery($filterQuery);
    }

    /**
     * Add a simple filter to the filters that MUST NOT fulfil the search items.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface $filterQuery
     *   The filter to add
     */
    public function addMustNotFilterQuery(NestedComponentInterface $filterQuery)
    {
        $this->mustNotFilterList->addFilterQuery($filterQuery);
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return 'europaSearch.componentProxy.searching.filters.queries.booleanQuery';
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
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param int                                                            $payload
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
