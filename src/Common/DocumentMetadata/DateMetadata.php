<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\DateMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a date metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class DateMetadata extends AbstractMetadata
{
    const TYPE = 'date';

    /**
     * DateMetadata constructor.
     *
     * @param string $name
     *   The raw name of the metadata.
     * @param array  $values
     *   The values of the metadata.
     *   It must contains valid date items only.
     */
    public function __construct($name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
        $this->type = self::TYPE;
    }

    /**
     * @inheritdoc
     *
     * @return string $type
     *   - 'date': for date that can be used to filter a search.
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for value.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $values = $this->getValues();
        foreach ($values as $key => $value) {
            // use procedural because the DateTime instantiation is
            // incompatible with the constraints mechanism.
            $date = date_create($value);
            if (!$date) {
                $context->buildViolation('The value is not a valid date.')
                    ->atPath('values['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * @inheritdoc
     *
     * @return array
     *   The converted metadata where:
     *   - The key is the metadata name formatted for the Europa Search services;
     *   - The value in the right format for for the Europa Search services.
     */
    public function encodeMetadata()
    {
        $name = $this->getEuropaSearchName();
        $values = $this->encodeMetadataDateValue($this->values);
        if (count($values) == 1) {
            $values = reset($values);
        }

        return array($name => $values);
    }

    /**
     * @inheritdoc
     *
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esDA_'.$this->name;
    }

    /**
     * Gets the date metadata values consumable by Europa Search service.
     *
     * @param array $values
     *   The raw date value to convert.
     * @return array
     *   The converted date values.
     */
    private function encodeMetadataDateValue($values)
    {
        $finalValues = array();
        foreach ($values as $item) {
            $dateTime = new \DateTime($item);
            $finalValues[] = $dateTime->format(\DateTime::ISO8601);
        }

        return $finalValues;
    }
}
