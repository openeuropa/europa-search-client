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
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
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
     */
    public function setProbability(float $probability): void
    {
        $this->probability = $probability;
    }
}
