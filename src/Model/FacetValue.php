<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Traits\ApiVersionAwareTrait;

/**
 * A class that represents a single facet value data object.
 */
class FacetValue
{
    use ApiVersionAwareTrait;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var string
     */
    protected $rawValue;

    /**
     * @var string
     */
    protected $value;

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param string $rawValue
     * @return $this
     */
    public function setRawValue(string $rawValue): self
    {
        $this->rawValue = $rawValue;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
