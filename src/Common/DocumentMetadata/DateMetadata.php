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
     */
    public function __construct($name)
    {
        $this->name = $name;
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
     * @inheritDoc
     *
     * @return string
     *   The name of the parse name as defined in the ParserProvider.
     *
     * @see EC\EuropaSearch\Index\Communication\Providers\ParserProvider
     */
    public static function getParserName()
    {
        return self::PARSER_NAME_PREFIX.'.'.self::TYPE;
    }
}
