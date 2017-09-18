<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Clauses\RangeClauseConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Clauses;

use EC\EuropaSearch\Messages\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IndexableMetadataInterface;
use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class RangeClauseConverter.
 *
 * It defines the default mechanism for parsing Range filter into a format that
 * is JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Clauses
 */
class RangeClauseConverter extends AbstractClauseConverter
{

    /**
     * {@inheritDoc}
     */
    public function convertComponent(ComponentInterface $component)
    {

        $metadata =  $component->getImpliedMetadata();
        $name = $metadata->getEuropaSearchName();
        $convertedValue = [$name => []];

        $lowerBoundary = $component->getLowerBoundary();
        if (!empty($lowerBoundary)) {
            $isIncluded = $component->isLowerBoundaryIncluded();
            $sign = ($isIncluded) ? 'gte' : 'gt';

            $lowerBoundary = $this->getBoundaryValue($lowerBoundary, $metadata);

            $convertedValue[$name][$sign] = $lowerBoundary;
        }

        $upperBoundary = $component->getUpperBoundary();
        if (!empty($upperBoundary)) {
            $isIncluded = $component->isUpperBoundaryIncluded();
            $sign = ($isIncluded) ? 'lte' : 'lt';

            $upperBoundary = $this->getBoundaryValue($upperBoundary, $metadata);

            $convertedValue[$name][$sign] = $upperBoundary;
        }

        $boost = $component->getBoost();
        if (isset($boost)) {
            $convertedValue['boost'] = $component->getBoost();
        }

        return ["range" => $convertedValue];
    }

    /**
     * Gets the boundary value to use in the conversion.
     *
     * @param mixed                      $rawValue
     *   The raw boundary value.
     * @param IndexableMetadataInterface $metadata
     *   The metadate implied in the filter.
     *
     * @return mixed
     *   The converted value.
     */
    private function getBoundaryValue($rawValue, $metadata)
    {

        if ($metadata instanceof DateMetadata) {
            return $this->getConvertedDateValue($rawValue);
        } elseif ($metadata instanceof BooleanMetadata) {
            return boolval($rawValue);
        }

        return $rawValue;
    }
}
