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
abstract class AbstractMetadata implements MetadataInterface
{
    const PARSER_NAME_PREFIX = 'parser.metadata';

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
     * @inheritdoc
     *
     * @param string $name
     *   The raw name of the metadata.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     *
     * @return string
     *   The raw name of the metadata.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     *
     * @return array
     *   The metadatat values.
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @inheritdoc
     *
     * @param array $values
     *   The metadata values.
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }

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
    }
}
