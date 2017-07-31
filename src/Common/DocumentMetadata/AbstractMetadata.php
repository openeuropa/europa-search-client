<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstraxt class AbstractMetadata.
 *
 * It represents a metadata of a indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
abstract class AbstractMetadata
{

    /**
     * Metadata name.
     *
     * @var string
     */
    protected $name;

    /**
     * Metadata values.
     *
     * @var
     */
    protected $values;

    /**
     * Metadata type.
     *
     * @var
     */
    protected $type;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     *   The metadata values. If the metadata type is 'date', the value type
     *   must be a valid date.
     */
    public function setValue(array $values)
    {
        $this->values = $values;
    }

    /**
     * Gets metadata types.
     *
     * @return string $type
     *   - 'fulltext': for string that can be included in a full text search;
     *   - 'uri': for URL or URI;
     *   - 'string': for string that can be used to filter a search;
     *   - 'integer': for integer that can be used to filter a search;
     *   - 'float': for float that can be used to filter a search;
     *   - 'boolean': for boolean that can be used to filter a search;
     *   - 'date': for date that can be used to filter a search;
     *   - 'not_indexed': for metadata that need to be send to Europa Search
     *      services but not indexed.
     */
    abstract public function getType();

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ]);
        $metadata->addPropertyConstraint('values', new Assert\Type('array'));
    }

    /**
     * Gets the final metadata name compliant for Europa Search services.
     *
     * @return string
     *   The final name.
     */
    abstract public function getEuropaSearchName();
}
