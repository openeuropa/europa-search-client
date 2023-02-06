<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class representing ingestion metadata.
 */
class Metadata implements \ArrayAccess, \Countable, \JsonSerializable
{
    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * @param array|null $metadata
     */
    public function __construct(?array $metadata = null)
    {
        $this->setMetadata($metadata);
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array|null $metadata
     * @return $this
     */
    public function setMetadata(?array $metadata): self
    {
        // Cast to array in order to overcome a potential null.
        foreach ((array) $metadata as $offset => $value) {
            $this->offsetSet($offset, $value);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        $this->validateOffset($offset);
        return isset($this->metadata[$offset]);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        $this->validateOffset($offset);
        return $this->metadata[$offset] ?? null;
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->validateOffset($offset);
        $this->validateValue($offset, $value);
        $this->metadata[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        $this->validateOffset($offset);
        unset($this->metadata[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->metadata);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) $this->metadata;
    }

    /**
     * @param mixed $offset
     */
    protected function validateOffset($offset): void
    {
        if (!is_string($offset) || empty($offset)) {
            throw new \InvalidArgumentException('Passed offset should be a non-empty string. Given: ' . var_export($offset, true));
        }
    }

    /**
     * @param mixed $value
     */
    protected function validateValue($offset, $value): void
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException("The metadata '{$offset}' value should be an array. Given: " . var_export($value, true));
        }
        array_walk($value, function ($item, $delta) use ($offset): void {
            if (!is_scalar($item)) {
                throw new \InvalidArgumentException("The metadata '{$offset}' value, delta {$delta}, should be a scalar. Given: " . var_export($item, true));
            }
        });
    }
}
