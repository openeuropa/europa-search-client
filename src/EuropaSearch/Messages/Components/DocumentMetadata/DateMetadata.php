<?php

namespace EC\EuropaSearch\Messages\Components\DocumentMetadata;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DateMetadata.
 *
 * Object for a date metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Messages\Components\DocumentMetadata
 */
class DateMetadata extends AbstractMetadata implements IndexableMetadataInterface
{

    const EUROPA_SEARCH_NAME_PREFIX = 'esDA';

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
     * Sets the metadata values based on a list of timestamp values.
     *
     * @param array $values
     *   The array of timestamp values to set.
     */
    public function setTimestampValues(array $values)
    {
        foreach ($values as &$value) {
            $value = is_numeric($value) ? (int) $value : strtotime($value);
            $dateTime = new \DateTime('@'.$value);
            $value = $dateTime ->format('d-m-Y H:i:s');
        }
        $this->setValues($values);
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
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     * @param mixed                                                          $payload
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
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'date';
    }
}
