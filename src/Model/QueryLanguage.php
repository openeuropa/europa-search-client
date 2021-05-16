<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a query language data transfer object.
 */
class QueryLanguage
{

    /**
     * The query language code.
     *
     * @var string
     */
    protected $language;

    /**
     * The query probability.
     *
     * @var float
     */
    protected $probability;

    /**
     * Returns the query language code.
     *
     * @return string
     *   The language code.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Sets the query language code.
     *
     * @param string $language
     *   The language code.
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Returns the query probability.
     *
     * @return float
     */
    public function getProbability(): float
    {
        return $this->probability;
    }

    /**
     * Sets the query probability.
     *
     * @param float $probability
     *   The query probability.
     * @return $this
     */
    public function setProbability(float $probability): self
    {
        $this->probability = $probability;
        return $this;
    }
}
