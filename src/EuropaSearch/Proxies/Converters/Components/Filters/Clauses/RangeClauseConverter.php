<?php

namespace OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Clauses;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\BooleanMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Class RangeClauseConverter.
 *
 * It defines the default mechanism for parsing Range filter into a format that
 * is JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Clauses
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
     * @param \OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface $metadata
     *   The metadata implied in the filter.
     *
     * @return mixed
     *   The converted value.
     */
    private function getBoundaryValue($rawValue, $metadata)
    {
        switch (get_class($metadata)) {
            case DateMetadata::class:
                $return = $this->getConvertedDateValue($rawValue);
                break;
            case BooleanMetadata::class:
                $return = boolval($rawValue);
                break;
            default:
                $return = $rawValue;
        }

        return $return;
    }
}
