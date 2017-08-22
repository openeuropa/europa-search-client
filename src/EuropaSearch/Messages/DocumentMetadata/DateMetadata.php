<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata.
 */

namespace EC\EuropaSearch\Messages\DocumentMetadata;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DateMetadata.
 *
 * Object for a date metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Messages\DocumentMetadata
 */
class DateMetadata extends AbstractMetadata implements IndexableMetadataInterface
{

    /**
     * DateMetadata constructor.
     *
     * @param string $name
     *   The raw name of the metadata.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
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
        // If not value are set, we stop the validation process.
        if (empty($values)) {
            return;
        }

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
     * {@inheritdoc}
     */
    public function getEuropaSearchName()
    {
        return 'esDA_'.$this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'date';
    }
}
