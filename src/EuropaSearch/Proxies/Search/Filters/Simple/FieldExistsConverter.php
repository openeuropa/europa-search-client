<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Simple\FieldExistsConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Simple;

use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class FieldExistsConverter.
 *
 * It defines the default mechanism for parsing FieldExists filter into a
 * format that is JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Simple
 */
class FieldExistsConverter extends AbstractSimpleConverter
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
