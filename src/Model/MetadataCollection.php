<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a collection of Metadata.
 */
class MetadataCollection implements \JsonSerializable
{
    /**
     * The list of metadata.
     * @var array
     */
    protected $collection = [];

    /**
     * Adds metadata objects to the metadata collection.
     *
     * @param string $key
     *   Usually a field name.
     * @param array $value
     *   The metadata value.
     *
     * @return $this
     */
    public function addMetadata(string $key, array $value): MetadataCollection
    {
        $metadata = new Metadata();
        $metadata->setKey($key)->setValue($value);
        $this->collection[] = $metadata;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $result = [];

        foreach ($this->collection as $metadata) {
            $result[$metadata->getKey()] = $metadata->getValue();
        }

        return $result;
    }
}
