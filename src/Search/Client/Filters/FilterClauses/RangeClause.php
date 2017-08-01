<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\RangeClause.
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RangeClause.
 *
 * It represents a criteria to filter on range.
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
     * RangeFilter constructor.
     *
     * @param string $impliedMetadataName
     *   The name of metadata implied in the filter.
     * @param string $impliedMetadataType
     *   The name of metadata implied in the filter.
     *   - 'fulltext': for string that can be included in a full text search;
     *   - 'uri': for URL or URI;
     *   - 'string': for string that can be used to filter a search;
     *   - 'int' or 'integer': for integer that can be used to filter a search;
     *   - 'float': for float that can be used to filter a search;
     *   - 'boolean': for boolean that can be used to filter a search;
     *   - 'date': for date that can be used to filter a search;
     *   - 'not_indexed': for metadata that need to be send to Europa Search
     *      services but not indexed.
     */
    public function __construct($impliedMetadataName, $impliedMetadataType)
    {
        $this->impliedMetadataName = $impliedMetadataName;
        $this->impliedMetadataType = $impliedMetadataType;
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
     * Sets the lower boundary to use in the filter definition.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param int  $lowerBoundary
     *  The lower boundary of the range filter.
     * @param bool $isIncluded
     *   Flag indicates of the lower boundary of the range must be included in the range.
     */
    public function setLowerBoundary($lowerBoundary, $isIncluded = false)
    {
        $this->lowerBoundary = $lowerBoundary;
        $this->isLowerIncluded = $isIncluded;
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
     * Sets the upper boundary to use in the filter definition.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param int  $upperBoundary
     *  The upper boundary of the range filter.
     * $isUpperIncluded
     * @param bool $isIncluded
     * Flag indicates of the upper boundary of the range must be included in the range.
     *
     */
    public function setUpperBoundary($upperBoundary, $isIncluded = false)
    {
        $this->upperBoundary = $upperBoundary;
        $this->isUpperIncluded = $isIncluded;
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
        $metadata->addPropertyConstraint('impliedMetadataType', new Assert\Choice([
                IntegerMetadata::TYPE,
                FloatMetadata::TYPE,
                DateMetadata::TYPE,
        ]));

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

        switch ($this->getImpliedMetadataType()) {
            case DateMetadata::TYPE:
                $this->validateDateRelatedClause($context, $payload);
                break;

            case FloatMetadata::TYPE:
                $this->validateFloatRelatedClause($context, $payload);
                break;

            case IntegerMetadata::TYPE:
                $this->validateIntRelatedClause($context, $payload);
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
