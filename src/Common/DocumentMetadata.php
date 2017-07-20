<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata.
 */

namespace EC\EuropaSearch\Common;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DocumentMetadata.
 *
 * It represents a metadata of a indexed document.
 *
 * @package EC\EuropaSearch\Common
 */
class DocumentMetadata
{

    /**
     * Metadata name.
     *
     * @var string
     */
    private $name;

    /**
     * Metadata value.
     *
     * @var
     */
    private $value;

    /**
     * Metadata type.
     *
     * @var
     */
    private $type;

    /**
     * Metadata boost level.
     *
     * @var
     */
    private $boost;

    /**
     * DocumentMetadata constructor.
     *
     * @param string $name
     *   The metadata name as know by the library consumer.
     * @param string $value
     *   The metadata value.
     * @param string $type
     *   The metadata value type.
     * @param int    $boost
     *   The boost value for the metadata; I.E. the importance if the metadata
     *   The search query.
     */
    public function __construct($name, $value, $type, $boost = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->boost = $boost;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * @param mixed $boost
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('type', new Assert\NotBlank());
        $metadata->addPropertyConstraint('type', new Assert\Type('string'));
        $metadata->addPropertyConstraint('value', new Assert\NotNull());
        $metadata->addPropertyConstraint('boost', new Assert\Type('int'));
    }
}
