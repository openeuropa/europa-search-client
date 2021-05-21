<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a document's metadata.
 */
class Metadata implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * Returns the list of values.
     *
     * @return array
     *   An array of values.
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * Add values.
     *
     * @param array $values
     *   The values to add.
     *
     * @return $this
     */
    public function setCollection(array $values): self
    {
        $this->collection = $values;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $collection = new \stdClass();

        foreach ($this->collection as $key => $value) {
            $collection->{$key} = $value;
        }
        return $collection;
    }
}
