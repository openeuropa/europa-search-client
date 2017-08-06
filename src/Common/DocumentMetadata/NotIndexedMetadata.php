<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a non-indexed metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class NotIndexedMetadata extends AbstractMetadata
{
    const TYPE = 'not_indexed';

    /**
     * StringMetadata constructor.
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
     *   - 'not_indexed': for metadata that need to be send to Europa Search
     *      services but not indexed.
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
        $metadata->addPropertyConstraint('values', new Assert\All(array('constraints' => array(new Assert\Type('string'), ), )));
    }

    /**
     * @inheritdoc
     *
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esNI_'.$this->name;
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
