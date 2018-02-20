<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RangeClause.
 *
 * It defines a filter on value range.
 *
 * It supports "DateMetadata", "FloatMetadata" and "IntegerMetadata" types.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses
 */
class RangeClause extends AbstractClause
{
    /**
     * The lower boundary to use in the filter definition.
     *
     * If the upper boundary is not set, the filter will define a
     * "greater than" criteria.
     *
     * @var int
     */
    protected $lowerBoundary;

    /**
     * Indicates if the lower boundary is included in the range.
     *
     * @var boolean
     */
    protected $isLowerIncluded = false;

    /**
     * The upper boundary to use in the filter definition.
     *
     * If the lower boundary is not set, the filter will define a
     * "less than" criteria.
     *
     * @var int
     */
    protected $upperBoundary;

    /**
     * Indicates if the upper boundary is included in the range.
     *
     * @var boolean
     */
    protected $isUpperIncluded = false;

    /**
     * Range constructor.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface $impliedMetadata
     *   The metadata pointed by the filters.
     */
    public function __construct(IndexableMetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
    }

    /**
     * Gets the lower boundary to use in the filter definition.
     *
     * @return int
     * The lower boundary.
     */
    public function getLowerBoundary()
    {
        return $this->lowerBoundary;
    }

    /**
     * Sets the filter lower boundary, it is excluded from the range.
     *
     * @param int $lowerBoundary
     *  The lower boundary of the range filter.
     */
    public function setLowerBoundaryExcluded($lowerBoundary)
    {
        $this->lowerBoundary = $lowerBoundary;
        $this->isLowerIncluded = false;
    }

    /**
     * Sets the filter lower boundary, it is included in the range.
     *
     * @param int $lowerBoundary
     *  The lower boundary of the range filter.
     */
    public function setLowerBoundaryIncluded($lowerBoundary)
    {
        $this->lowerBoundary = $lowerBoundary;
        $this->isLowerIncluded = true;
    }

    /**
     * Gets the upper boundary to use in the filter definition.
     *
     * @return int
     * The upper boundary.
     */
    public function getUpperBoundary()
    {
        return $this->upperBoundary;
    }

    /**
     * Sets the filter upper boundary, it is excluded from the range.
     *
     * @param int $upperBoundary
     *  The upper boundary of the range filter.
     *
     */
    public function setUpperBoundaryExcluded($upperBoundary)
    {
        $this->upperBoundary = $upperBoundary;
        $this->isUpperIncluded = false;
    }

    /**
     * Sets the filter upper boundary, it is included in the range.
     *
     * @param int $upperBoundary
     *  The upper boundary of the range filter.
     *
     */
    public function setUpperBoundaryIncluded($upperBoundary)
    {
        $this->upperBoundary = $upperBoundary;
        $this->isUpperIncluded = true;
    }

    /**
     * Indicates if the lower boundary mus teb included or not.
     *
     * @return boolean
     *   True if it must be included; otherwise false.
     */
    public function isLowerBoundaryIncluded()
    {
        return $this->isLowerIncluded;
    }

    /**
     * Indicates if the upper boundary mus teb included or not.
     *
     * @return boolean
     *   True if it must be included; otherwise false.
     */
    public function isUpperBoundaryIncluded()
    {
        return $this->isUpperIncluded;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'range';
    }


    /**
     * Loads constraints declarations for the validator process.
     *
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('isLowerIncluded', new Assert\Type('bool'));
        $metadata->addPropertyConstraint('isUpperIncluded', new Assert\Type('bool'));
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for rangeFilter.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (!isset($this->lowerBoundary) && !isset($this->upperBoundary)) {
            $context->buildViolation('At least a boundary must be set.')
                ->addViolation();
        }

        $metadata = $this->getImpliedMetadata();
        switch (true) {
            case ($metadata instanceof DateMetadata):
                $this->validateDateRelatedFilter($context, $payload);
                break;

            case ($metadata instanceof FloatMetadata):
                $this->validateFloatRelatedFilter($context, $payload);
                break;

            case ($metadata instanceof IntegerMetadata):
                $this->validateIntRelatedFilter($context, $payload);
                break;

            default:
                $context->buildViolation('This metadata is not supported for this kind of filter.')
                    ->atPath('impliedMetadata')
                    ->addViolation();
                break;
        }
    }

    /**
     * Special validator callback for rangeFilter related to a DateMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    protected function validateDateRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        if (isset($this->lowerBoundary) && !date_create($this->lowerBoundary)) {
            $context->buildViolation('The lower boundary is not a valid date.')
                ->atPath('lowerBoundary')
                ->addViolation();
        }
        if (isset($this->upperBoundary) && !date_create($this->upperBoundary)) {
            $context->buildViolation('The upper boundary is not a valid date.')
                ->atPath('upperBoundary')
                ->addViolation();
        }
    }

    /**
     * Special validator callback for rangeFilter related to a FloatMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    protected function validateFloatRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        if (isset($this->lowerBoundary) && !(is_int($this->lowerBoundary) || is_float($this->lowerBoundary))) {
            $context->buildViolation('The lower boundary is not a valid numeric (int or float).')
                ->atPath('lowerBoundary')
                ->addViolation();
        }
        if (isset($this->upperBoundary) && !(is_int($this->upperBoundary) || is_float($this->upperBoundary))) {
            $context->buildViolation('The upper boundary is not a valid numeric (int or float).')
                ->atPath('upperBoundary')
                ->addViolation();
        }
    }

    /**
     * Special validator callback for rangeFilter related to a IntegerMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    protected function validateIntRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        if (isset($this->lowerBoundary) && !is_int($this->lowerBoundary)) {
            $context->buildViolation('The lower boundary is not a valid integer.')
                ->atPath('lowerBoundary')
                ->addViolation();
        }
        if (isset($this->upperBoundary) && !is_int($this->upperBoundary)) {
            $context->buildViolation('The upper boundary is not a valid integer.')
                ->atPath('upperBoundary')
                ->addViolation();
        }
    }
}
