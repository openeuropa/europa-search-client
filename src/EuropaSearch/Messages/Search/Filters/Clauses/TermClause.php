<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Clauses\TermClause.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Clauses;

use EC\EuropaSearch\Messages\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IndexableMetadataInterface;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\URLMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TermClause.
 *
 * It defines a filter on one metadata value of scalar type.
 *
 * It does not support "NotIndexedMetadata" metadata type.
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Clauses
 */
class TermClause extends AbstractClause
{

    /**
     * The value to use in the filter definition.
     *
     * @var string
     */
    private $testedValue;

    /**
     * Value constructor.
     *
     * @param IndexableMetadataInterface $impliedMetadata
     *   The metadata pointed by the filters.
     */
    public function __construct(IndexableMetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
    }

    /**
     * Gets the value(s) to use in the filter definition.
     *
     * @return string
     * The value.
     */
    public function getTestedValue()
    {
        return $this->testedValue;
    }

    /**
     * Sets the values to use in the filter definition.
     *
     * @param string $testedValue
     *  The values.
     */
    public function setTestedValue($testedValue)
    {
        $this->testedValue = $testedValue;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'term';
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraints('testedValue', [
            new Assert\NotBlank(),
            new Assert\Type('scalar'),
        ]);
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for valueFilter.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

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

            case ($metadata instanceof BooleanMetadata):
                $this->validateBooleanRelatedFilter($context, $payload);
                break;

            case ($metadata instanceof URLMetadata):
                $this->validateURLRelatedFilter($context, $payload);
                break;

            case ($metadata instanceof URLMetadata):
                $this->validateURLRelatedFilter($context, $payload);
                break;
        }
    }

    /**
     * Special validator callback for valueFilter related to a DateMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateDateRelatedFilter(ExecutionContextInterface $context, $payload)
    {

        if (!date_create($this->testedValue)) {
            $context->buildViolation('The tested value is not a valid date.')
                ->atPath('testedValue')
                ->addViolation();
        }
    }

    /**
     * Special validator callback for valueFilter related to a FloatMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateFloatRelatedFilter(ExecutionContextInterface $context, $payload)
    {

        if (!is_float($this->testedValue)) {
            $context->buildViolation('The tested value is not a valid float.')
                ->atPath('testedValue')
                ->addViolation();
        }
    }

    /**
     * Special validator callback for valueFilter related to a IntegerMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateIntRelatedFilter(ExecutionContextInterface $context, $payload)
    {

        if (!is_int($this->testedValue)) {
            $context->buildViolation('The tested value is not a valid integer.')
                ->atPath('testedValue')
                ->addViolation();
        }
    }

    /**
     * Special validator callback for valueFilter related to a BooleanMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateBooleanRelatedFilter(ExecutionContextInterface $context, $payload)
    {

        if (!is_bool($this->testedValue)) {
            $context->buildViolation('The tested value is not a valid boolean.')
                ->atPath('testedValue')
                ->addViolation();
        }
    }

    /**
     * Special validator callback for valueFilter related to a URLMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateURLRelatedFilter(ExecutionContextInterface $context, $payload)
    {

        $validator = new Assert\UrlValidator();
        $validator->initialize($context);
        // Re-inject the validator in the context in order to point on
        // the "testedValue" path.
        $validator = $context->getValidator()->inContext($context);
        $validator->atPath('testedValue')->validate($this->testedValue, new Assert\Url());
    }
}
