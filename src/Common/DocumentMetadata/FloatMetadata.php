<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a float metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class FloatMetadata extends AbstractMetadata
{
    const TYPE = 'float';

    /**
     * IntDocumentMetadata constructor.
     *
     * @param string $name
     *   The raw name of the metadata.
     * @param array  $values
     *   The values of the metadata.
     *   It must contains integer items only.
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
     *   - 'float': for float that can be used to filter a search.
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
        $metadata->addPropertyConstraint('values', new Assert\All(array('constraints' => array(new Assert\Type('float'), ), )));
    }

    /**
     * @inheritdoc
     *
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esNU_'.$this->name;
    }
}
