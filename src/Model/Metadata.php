<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a document's metadata.
 */
class Metadata
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $value;

    /**
     * Returns the key name.
     *
     * @return string
     *   The metadata key.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the object key.
     *
     * @param string $key
     *   The object key name.
     *
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Returns the list of values.
     *
     * @return array
     *   An array of field values.
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * Sets the object value.
     *
     * @param array $value
     *   The object value.
     *
     * @return $this
     */
    public function setValue(array $value): self
    {
        $this->value = $value;

        return $this;
    }
}
