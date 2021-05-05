<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents the fields metadata.
 */
class Metadata
{
    /**
     * The object name.
     *
     * @var string
     */
    protected $key;

    /**
     * An array of values.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\Metadata[]
     */
    protected $value;

    /**
     * Returns the key name.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Metadata
     *   An array of field values.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the object key.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\Metadata $key
     *   The object key name.
     *
     * @return $this
     */
    public function setKey(string $key): Metadata
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Returns the list of values.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Metadata[]
     *   An array of field values.
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * Sets the object value.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\Metadata[] $value
     *   The object value.
     *
     * @return $this
     */
    public function setValue(array $value): Metadata
    {
        $this->value = $value;

        return $this;
    }
}
