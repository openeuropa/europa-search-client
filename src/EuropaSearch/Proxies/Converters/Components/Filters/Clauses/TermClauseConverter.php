<?php

namespace EC\EuropaSearch\Proxies\Converters\Components\Filters\Clauses;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Class TermClauseConverter.
 *
 * It defines the default mechanism for parsing Term filter into a format that
 * is JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components\Filters\Clauses
 */
class TermClauseConverter extends AbstractClauseConverter
{

    /**
     * {@inheritDoc}
     */
    public function convertComponent(ComponentInterface $component)
    {
        $metadata = $component->getImpliedMetadata();
        $name = $metadata->getEuropaSearchName();

        $convertedValue = $component->getTestedValue();

        if ($metadata instanceof DateMetadata) {
            $convertedValue = $this->getConvertedDateValue($convertedValue);
        } elseif ($metadata instanceof BooleanMetadata) {
            $convertedValue = boolval($convertedValue);
        }

        $convertedValue = [$name => $convertedValue];

        $boost = $component->getBoost();
        if (isset($boost)) {
            $convertedValue['boost'] = $component->getBoost();
        }

        return ["term" => $convertedValue];
    }
}
