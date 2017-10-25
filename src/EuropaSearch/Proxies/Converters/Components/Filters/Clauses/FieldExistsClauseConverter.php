<?php

namespace EC\EuropaSearch\Proxies\Converters\Components\Filters\Clauses;

use EC\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Class FieldExistsClauseConverter.
 *
 * It defines the default mechanism for parsing FieldExistsClause filter into a
 * format that is JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components\Filters\Clauses
 */
class FieldExistsClauseConverter extends AbstractClauseConverter
{

    /**
     * {@inheritDoc}
     */
    public function convertComponent(ComponentInterface $component)
    {
        $name = $component->getImpliedMetadata()->getEuropaSearchName();
        $convertedValue = ['field' => $name];

        $boost = $component->getBoost();
        if (isset($boost)) {
            $convertedValue['boost'] = $component->getBoost();
        }

        return ["exists" => $convertedValue];
    }
}
