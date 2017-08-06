<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\ValuesClause.
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Clause to filter on one metadata.
 *
 * @inheritdoc
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
class ValuesClause extends AbstractClause
{
    /**
     * The values to use in the filter definition.
     *
     * @var array
     */
    private $testedValues;

    /**
     * ValuesClause constructor.
     *
     * @param \EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface $impliedMetadata
     *   The metadata implied in the filter.
     */
    public function __construct(MetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
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
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('impliedMetadata', new Assert\Expression(array(
            'expression' => 'this.getMetadataType() !== "'.NotIndexedMetadata::TYPE.'"',
            'message' => 'The metadata is not supported for this kind of clause.',
        )));

        $metadata->addPropertyConstraint('testedValues', new Assert\NotBlank());

        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for valuesClause.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
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

            case BooleanMetadata::TYPE:
                $this->validateBooleanRelatedClause($context, $payload);
                break;

            case URLMetadata::TYPE:
                $this->validateURLRelatedClause($context, $payload);
                break;
        }
    }

    /**
     * Special validator callback for valuesClause related to a DateMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateDateRelatedClause(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!date_create($testedValue)) {
                $context->buildViolation('The tested value is not a valid date.')
                    ->atPath('testedValue['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesClause related to a FloatMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateFloatRelatedClause(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!is_float($testedValue)) {
                $context->buildViolation('The tested value is not a valid float.')
                    ->atPath('testedValue['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesClause related to a IntegerMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateIntRelatedClause(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!is_int($testedValue)) {
                $context->buildViolation('The tested value is not a valid int.')
                    ->atPath('testedValue['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesClause related to a BooleanMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateBooleanRelatedClause(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            if (!is_bool($testedValue)) {
                $context->buildViolation('The tested value is not a valid boolean.')
                    ->atPath('testedValue['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * Special validator callback for valuesClause related to a URLMetadata.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    private function validateURLRelatedClause(ExecutionContextInterface $context, $payload)
    {
        foreach ($this->testedValues as $key => $testedValue) {
            $validator = new Assert\UrlValidator();
            $validator->initialize($context);
            // Re-inject the validator in the context in order to point on
            // the "testedValue" path.
            $validator = $context->getValidator()->inContext($context);
            $validator->atPath('testedValue['.$key.']')->validate($testedValue, new Assert\Url());
        }
    }
}
