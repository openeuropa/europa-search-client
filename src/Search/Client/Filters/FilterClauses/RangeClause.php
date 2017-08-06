<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\RangeClause.
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Clause to filter on range.
 *
 * @inheritdoc
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
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
    private $lowerBoundary;

    /**
     * Indicates if the lower boundary is included in the range.
     *
     * @var boolean
     */
    private $isLowerIncluded = false;

    /**
     * The upper boundary to use in the filter definition.
     *
     * If the lower boundary is not set, the filter will define a
     * "less than" criteria.
     *
     * @var int
     */
    private $upperBoundary;

    /**
     * Indicates if the upper boundary is included in the range.
     *
     * @var boolean
     */
    private $isUpperIncluded = false;

    /**
     * RangeClause constructor.
     *
     * @param \EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface $impliedMetadata
     *   The metadata implied in the filter.
     */
    public function __construct(MetadataInterface $impliedMetadata)
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
    public function isLowerIncluded()
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
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('isLowerIncluded', new Assert\Type('bool'));
        $metadata->addPropertyConstraint('isUpperIncluded', new Assert\Type('bool'));
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for rangeClause.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (!isset($this->lowerBoundary) && !isset($this->upperBoundary)) {
            $context->buildViolation('At least a boundary must be set.')
                ->addViolation();
        }

        switch ($this->getMetadataType()) {
            case DateMetadata::TYPE:
                $this->validateDateRelatedClause($context, $payload);
                break;

            case FloatMetadata::TYPE:
                $this->validateFloatRelatedClause($context, $payload);
                break;

            case IntegerMetadata::TYPE:
                $this->validateIntRelatedClause($context, $payload);
                break;

            default:
                $context->buildViolation('The metadata is not supported for this kind of clause.')
                    ->atPath('impliedMetadata')
                    ->addViolation();
                break;
        }
    }

    /**
     * Special validator callback for rangeClause related to a DateMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateDateRelatedClause(ExecutionContextInterface $context, $payload)
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
     * Special validator callback for rangeClause related to a FloatMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateFloatRelatedClause(ExecutionContextInterface $context, $payload)
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
     * Special validator callback for rangeClause related to a IntegerMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateIntRelatedClause(ExecutionContextInterface $context, $payload)
    {
        if (isset($this->lowerBoundary) && !is_int($this->lowerBoundary)) {
            $context->buildViolation('The lower boundary is not a valid int.')
                ->atPath('lowerBoundary')
                ->addViolation();
        }
        if (isset($this->upperBoundary) && !is_int($this->upperBoundary)) {
            $context->buildViolation('The upper boundary is not a valid int.')
                ->atPath('upperBoundary')
                ->addViolation();
        }
    }
}
