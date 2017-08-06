<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a boolean metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class BooleanMetadata extends AbstractMetadata
{
    const TYPE = 'boolean';

    /**
     * BooleanMetadata constructor.
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
     *   - 'boolean': for boolean that can be used to filter a search.
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
        $metadata->addPropertyConstraint('values', new Assert\All(array('constraints' => array(new Assert\Type('bool'), ), )));
    }

    /**
     * @inheritdoc
     *
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esBO_'.$this->name;
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
