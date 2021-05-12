<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a facet data transfer object.
 */
class Facet
{
    /**
     * The facet database.
     *
     * @var string|null
     */
    protected $database;

    /**
     * The total number of results.
     *
     * @var int
     */
    protected $count;

    /**
     * The facet label that can be used in the search interface display.
     *
     * @var string
     */
    protected $name;

    /**
     * The facet name that can be used as id key between ES services and the Drupal implementation.
     *
     * @var string
     */
    protected $rawName;

    /**
     * An array of children facets.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\Facet[]
     */
    protected $values;

    /**
     * @return string|null
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @param string|null $database
     */
    public function setDatabase(?string $database): void
    {
        $this->database = $database;
    }

    /**
     * Returns the number of results found for this facet.
     *
     * @return int
     *   The number of result in the facet.
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Sets the number of results found for this facet.
     *
     * @param int $count
     *   The number of result in the facet.
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * Returns the facet label.
     *
     * @return string
     *   The facet label.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the facet label.
     *
     * @param string $name
     *   The facet label.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the facet name.
     *
     * @return string
     *   The facet name.
     */
    public function getRawname(): string
    {
        return $this->rawName;
    }

    /**
     * Sets the facet name.
     *
     * @param string $rawName
     *   The facet name.
     */
    public function setRawname(string $rawName): void
    {
        $this->rawName = $rawName;
    }

    /**
     * Returns the list of facet values.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Facet[]
     *   An array of child facets.
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Sets the list of facet values.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\Facet[] $values
     *   An array of child facets.
     */
    public function setValues(array $values): void
    {
        $this->values = $values;
    }
}
