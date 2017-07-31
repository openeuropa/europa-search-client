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
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esDA_'.$this->name;
    }
}
