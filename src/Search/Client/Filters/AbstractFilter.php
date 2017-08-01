<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\AbstractFilter.
 */
namespace EC\EuropaSearch\Search\Client\Filters;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract class AbstractFilter.
 *
 * @package EC\EuropaSearch\Search\Client\Filters
 */
abstract class AbstractFilter
{

    /**
     * The importance of the filter in the search query.
     *
     * @var float
     */
    private $boost;

    /**
     * Gets the boost value defined with the filter.
     *
     * @return float
     *   The boost value
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     *
     * Sets the boost value defined with the filter.
     *
     * @param float $boost
     *   The boost value
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('boost', new Assert\Type('int'));
    }
}
