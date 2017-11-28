<?php

namespace EC\EuropaSearch\Messages\Components\Filters\Clauses;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\URLMetadata;
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
 * @package EC\EuropaSearch\Messages\Components\Filters\Clauses
 */
class TermClause extends AbstractClause
{

    /**
     * The value to use in the filter definition.
     *
     * @var string
     */
    protected $testedValue;

    /**
     * Value constructor.
     *
     * @param \EC\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface $impliedMetadata
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
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
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
     * Special validator callback for valueFilter related to a DateMetadata.
     *
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateDateRelatedFilter(ExecutionContextInterface $context, $payload)
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
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateFloatRelatedFilter(ExecutionContextInterface $context, $payload)
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
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateIntRelatedFilter(ExecutionContextInterface $context, $payload)
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
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateBooleanRelatedFilter(ExecutionContextInterface $context, $payload)
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
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
     */
    protected function validateURLRelatedFilter(ExecutionContextInterface $context, $payload)
    {

        $validator = new Assert\UrlValidator();
        $validator->initialize($context);
        // Re-inject the validator in the context in order to point on
        // the "testedValue" path.
        $validator = $context->getValidator()->inContext($context);
        $validator->atPath('testedValue')->validate($this->testedValue, new Assert\Url());
    }
}
