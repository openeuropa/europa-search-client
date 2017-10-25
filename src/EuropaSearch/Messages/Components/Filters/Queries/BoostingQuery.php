<?php

namespace EC\EuropaSearch\Messages\Components\Filters\Queries;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\AbstractNumericMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Components\Filters\BoostableFilter;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\TermClause;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\TermsClause;
use EC\EuropaSearch\Messages\Components\NestedComponentInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BoostingQuery.
 *
 * Represents a Boolean query compound of Europa Search; I.E:
 * "Allows you to bias some of the fields, positively or negatively.
 * Unlike the boolean query, even if the field does not match it will still
 * be returned.
 * This is just for the ranking of the results."
 *
 * It only supports "Value" and "Values" objects that involve
 * "StringMetadata" or "NumericMetadata".
 *
 * @package EC\EuropaSearch\Messages\Components\Filters\Combined
 */
class BoostingQuery extends BoostableFilter implements NestedComponentInterface
{

    /**
     * List of filters contributing to bias fields positively.
     *
     * @var array
     */
    private $positiveFilters;

    /**
     * List of filters contributing to bias fields negatively.
     *
     * @var array
     */
    private $negativeFilters;

    /**
     * BoostingQuery constructor.
     */
    public function __construct()
    {
        $this->positiveFilters = new FilterQueryComponent('positive');
        $this->negativeFilters = new FilterQueryComponent('negative');
    }

    /**
     * Gets the list of filters contributing to bias fields positively.
     *
     * @return array
     *   The list of filters.
     */
    public function getPositiveFilters()
    {
        return $this->positiveFilters;
    }

    /**
     * Gets the list of filters contributing to bias fields negatively.
     *
     * @return array
     *   The list of filters.
     */
    public function getNegativeFilters()
    {
        return $this->negativeFilters;
    }

    /**
     * Add a Value in the list of positive.
     *
     * @param TermClause $filterClause
     *   The filter to add.
     */
    public function addValueInPositiveFilters(TermClause $filterClause)
    {
        $this->positiveFilters->addFilterClause($filterClause);
    }

    /**
     *Add a Values in the list of positive.
     *
     * @param TermsClause $filterClause
     *   The filter to add.
     */
    public function addValuesInPositiveFilters(TermsClause $filterClause)
    {
        $this->positiveFilters->addFilterClause($filterClause);
    }

    /**
     * Add a Value in the list of negative.
     *
     * @param TermClause $filterClause
     *   The filter to add.
     */
    public function addValueInNegativeFilters(TermClause $filterClause)
    {
        $this->negativeFilters->addFilterClause($filterClause);
    }

    /**
     * Add a Values in the list of negative.
     *
     * @param TermsClause $filterClause
     *   The filter to add.
     */
    public function addValuesInNegativeFilters(TermsClause $filterClause)
    {
        $this->negativeFilters->addFilterClause($filterClause);
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return 'componentProxy.searching.filters.queries.boostingQuery';
    }

    /**
     * {@inheritDoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('negativeFilters', new Assert\Valid());
        $metadata->addPropertyConstraint('positiveFilters', new Assert\Valid());
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for BoostingQuery.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (empty($this->getPositiveFilters()) && empty($this->getNegativeFilters())) {
            $context->buildViolation('At least one of the filter list must filled.')
                ->atPath('positiveFilters')
                ->addViolation();
        }
        // Test negative list
        $filterList = $this->negativeFilters->getFilterList();
        $this->validateFilter($filterList, 'negativeFilters', $context, $payload);

        // Test positive list
        $filterList = $this->positiveFilters->getFilterList();
        $this->validateFilter($filterList, 'positiveFilters', $context, $payload);
    }

    /**
     * Validates a filter list defined in the current BoostingQuery.
     *
     * @param array                     $filterList
     *   The filter list to validate.
     * @param string                    $checkProperty
     *   The checked property path
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validateFilter(array $filterList, $checkProperty, ExecutionContextInterface $context, $payload)
    {
        foreach ($filterList as $key => $filter) {
            $filterMetadata = $filter->getImpliedMetadata();

            if ((!$filterMetadata instanceof StringMetadata) && (!$filterMetadata instanceof AbstractNumericMetadata)) {
                $context->buildViolation('The Metadata implied in the filter is not supported. Only text and numerical ones are valid.')
                    ->atPath($checkProperty.'['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getChildComponents()
    {
        return [
            $this->positiveFilters,
            $this->negativeFilters,
        ];
    }
}
