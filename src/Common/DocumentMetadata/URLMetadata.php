<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\URLMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a URL metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class URLMetadata extends AbstractMetadata
{
    const TYPE = 'uri';

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
     *   - 'uri': for URL or URI.
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
        $metadata->addPropertyConstraint('values', new Assert\All(array('constraints' => array(new Assert\Url(), ), )));
    }

    /**
     * @inheritdoc
     *
     * It gets the same prefix of "String" metadata.
     *
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esST_'.$this->name;
    }
}
