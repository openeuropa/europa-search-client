<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\BooleanMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\URLMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TermsClause.
 *
 * It defines a filter on a list of metadata values of scalar type.
 *
 * It does not support "NotIndexedMetadata" metadata type.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses
 */
class TermsClause extends AbstractClause
{
    /**
     * The values to use in the filter definition.
     *
     * @var array
     */
    protected $testedValues;

    /**
     * FieldExist constructor.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface $impliedMetadata
     *   The metadata pointed by the filters.
     */
    public function __construct(IndexableMetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'terms';
    }

    /**
     * Gets the values to use in the filter definition.
     *
     * @return array
     * The values.
     */
    public function getTestedValues()
    {
        return $this->testedValues;
    }

    /**
     * Sets the values to use in the filter definition.
     *
     * @param array $testedValues
     *  The values.
     */
    public function setTestedValues($testedValues)
    {
        $this->testedValues = $testedValues;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('testedValues', new Assert\NotBlank());
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for valuesFilter.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
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
        }
    }

    /**
     * Special validator callback for valuesFilter related to a DateMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateDateRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!date_create($testedValue)) {
                $context->buildViolation('The tested value is not a valid date.')
                    ->atPath('testedValues['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesFilter related to a FloatMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateFloatRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!is_float($testedValue)) {
                $context->buildViolation('The tested value is not a valid float.')
                    ->atPath('testedValues['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesFilter related to a IntegerMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateIntRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!is_int($testedValue)) {
                $context->buildViolation('The tested value is not a valid integer.')
                    ->atPath('testedValues['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesFilter related to a BooleanMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateBooleanRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!is_bool($testedValue)) {
                $context->buildViolation('The tested value is not a valid boolean.')
                    ->atPath('testedValues['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesFilter related to a URLMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateURLRelatedFilter(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            $validator = new Assert\UrlValidator();
            $validator->initialize($context);
            // Re-inject the validator in the context in order to point on
            // the "testedValue" path.
            $validator = $context->getValidator()->inContext($context);
            $validator->atPath('testedValues['.$key.']')->validate($testedValue, new Assert\Url());
        }
    }
}
