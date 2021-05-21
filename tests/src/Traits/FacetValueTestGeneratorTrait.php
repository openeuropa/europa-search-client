<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Traits;

use OpenEuropa\EuropaSearchClient\Model\FacetValue;

trait FacetValueTestGeneratorTrait
{
    /**
     * @param array $values
     *   Values with keys as FacetValue protected property names.
     * @return \OpenEuropa\EuropaSearchClient\Model\FacetValue
     */
    protected function generateTestingFacetValue(array $values): FacetValue
    {
        return (new FacetValue())
            ->setApiVersion($values['apiVersion'])
            ->setCount($values['count'])
            ->setRawValue($values['rawValue'])
            ->setValue($values['value']);
    }
}
