<?php

namespace EC\EuropaSearch\Messages\Components\Filters;

/**
 * Class BoostableFilter.
 *
 * Exteending this class allows Filter object to include the boost parameter.
 *
 * @package EC\EuropaSearch\Messages\Components\Filters
 */
class BoostableFilter
{
    /**
     * Boost value.
     *
     * @var int
     */
    protected $boost;

    /**
     * Gets the boost value.
     *
     * @return int
     *   The boost value.
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * Gets the boost value.
     *
     * @param int $boost
     *   The boost value.
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
    }
}
