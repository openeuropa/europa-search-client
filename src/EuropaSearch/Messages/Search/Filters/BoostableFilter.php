<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\BoostableFilter.
 */

namespace EC\EuropaSearch\Messages\Search\Filters;

/**
 * Class BoostableFilter.
 *
 * Exteending this class allows Filter object to include the boost parameter.
 *
 * @package EC\EuropaSearch\Messages\Search\Filters
 */
class BoostableFilter
{
    /**
     * Boost value.
     *
     * @var int
     */
    private $boost;

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
