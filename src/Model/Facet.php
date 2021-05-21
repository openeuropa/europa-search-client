<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Traits\ApiVersionAwareTrait;

/**
 * A class that represents a facet data transfer object.
 */
class Facet
{
    use ApiVersionAwareTrait;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $rawName;

    /**
     * @var FacetValue[]
     * @todo This will be converted to a collection in OEL-166.
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-166
     */
    protected $values;

    /**
     * @param string $database
     * @return $this
     */
    public function setDatabase(string $database): self
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

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
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $rawName
     * @return $this
     */
    public function setRawName(string $rawName): self
    {
        $this->rawName = $rawName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawName(): string
    {
        return $this->rawName;
    }

    /**
     * @param Facet[] $values
     * @return $this
     * @todo The $values parameter will be converted to a collection in OEL-166.
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-166
     */
    public function setValues(array $values): self
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @return Facet[]
     * @todo This will be converted to a collection in OEL-166.
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-166
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
