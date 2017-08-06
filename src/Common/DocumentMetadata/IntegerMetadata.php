<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a integer metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class IntegerMetadata extends AbstractNumericMetadata
{
    const TYPE = 'integer';

    /**
     * IntDocumentMetadata constructor.
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
     *   - 'integer': for integer that can be used to filter a search.
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
        $metadata->addPropertyConstraint('values', new Assert\All(array('constraints' => array(new Assert\Type('int'), ), )));
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
