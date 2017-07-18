<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata
 */

namespace EC\EuropaSearch\Common;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DocumentMetadata
 * @package EC\EuropaSearch
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
        $metadata->addPropertyConstraint('value', new Assert\NotNull());
        $metadata->addPropertyConstraint('boost', new Assert\Type('int'));
    }
}
