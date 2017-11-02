<?php

namespace EC\EuropaSearch\Messages\Components\DocumentMetadata;

use EC\EuropaSearch\Messages\Components\ComponentInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractMetadata.
 *
 * It represents a generic document metadata.
 *
 * @package EC\EuropaSearch\Messages\Components\DocumentMetadata
 *
 * {@internal It only exists for only declaring methods common to sub-classes.}
 */
abstract class AbstractMetadata implements ComponentInterface
{

    /**
     * Prefix applicable to all Metadata.
     *
     * @const
     */
    const EUROPA_SEARCH_NAME_PREFIX = '';

    /**
     * Prefix applicable to all converter id of classes extending this class.
     *
     * @const
     */
    const CONVERTER_NAME_PREFIX = 'europaSearch.componentProxy.metadata.';

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
     * Sets the metadata name.
     *
     * @param string $name
     *   The metadata name.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the metadata name.
     *
     * @return string
     *   The metadata name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the metadata values
     *
     * @param array $values
     *   The metadata values.
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }

  /**
   * Sets the metadata values by formatting them first.
   *
   * @param array $values
   *   The raw values to format and to set.
   */
    public function setRawValues($values)
    {
        $this->setValues($values);
    }

    /**
     * Gets the metadata values
     *
     * @return mixed $values
     *   The metadata values.
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Gets the final metadata name compliant for Europa Search services.
     *
     * @return string
     *   The final name.
     *
     * {@internal Used by the library process, should not be called outside.}
     */
    public function getEuropaSearchName()
    {
        return $this::EUROPA_SEARCH_NAME_PREFIX.'_'.$this->name;
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ]);
    }
}
