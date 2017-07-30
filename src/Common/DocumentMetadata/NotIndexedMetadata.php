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
}
