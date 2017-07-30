<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\FullTextMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a full-text searchable metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class FullTextMetadata extends AbstractMetadata
{
    const TYPE = 'fulltext';

    /**
     * StringMetadata constructor.
     *
     * @param string $name
     *   The raw name of the metadata.
     * @param array  $values
     *   The values of the metadata.
     *   If it is an array, it must contains string items only.
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
     *   - 'fulltext': for string that can be included in a full text search.
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
        return 'esIN_'.$this->name;
    }
}
