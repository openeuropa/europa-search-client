<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Traits;

use OpenEuropa\EuropaSearchClient\Model\Facet;
use OpenEuropa\EuropaSearchClient\Model\FacetValue;

trait FacetTestGeneratorTrait
{
    /**
     * @param int $count
     * @return array
     *   An array with a list of facet values as raw array.
     */
    protected function generateTestingFacetValuesAsArray(int $count): array
    {
        $facetValuesAsArray = [];
        for ($i = 0; $i < $count; $i++) {
            $toShuffle = md5(serialize($facetValuesAsArray));
            $facetValuesAsArray[] = [
                'apiVersion' => str_shuffle($toShuffle),
                'count' => rand(1, 100000),
                'rawValue' => str_shuffle($toShuffle),
                'value' => str_shuffle($toShuffle),
            ];
        }

        return $facetValuesAsArray;
    }

    /**
     * @param array $facetValuesAsArray
     * @return FacetValue[]
     */
    protected function convertTestingFacetValuesToObject(array $facetValuesAsArray): array
    {
        return array_map(
            [$this, 'generateTestingFacetValue'],
            $facetValuesAsArray
        );
    }

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

    /**
     * @param int $count
     * @return array
     *   A list of facet items as raw array.
     */
    protected function generateTestingFacetItemsAsArray(int $count): array
    {
        $facetItemsAsArray = [];
        for ($i = 0; $i < $count; $i++) {
            $toShuffle = md5(serialize($facetItemsAsArray));
            $facetValuesAsArray = $this->generateTestingFacetValuesAsArray(rand(2, 7));
            $facetItemsAsArray[] = [
                'apiVersion' => str_shuffle($toShuffle),
                'count' => rand(1, 100000),
                'database' => str_shuffle($toShuffle),
                'rawName' => str_shuffle($toShuffle),
                'name' => str_shuffle($toShuffle),
                'values' => $facetValuesAsArray,
            ];
        }

        return $facetItemsAsArray;
    }

    /**
     * @param array $facetItemsAsArray
     * @return Facet[]
     */
    protected function convertTestingFacetItemsToObject(array $facetItemsAsArray): array
    {
        return array_map(
            [$this, 'generateTestingFacet'],
            $facetItemsAsArray
        );
    }

    /**
     * @param array $values
     *   Values with keys as Facet protected property names.
     * @return \OpenEuropa\EuropaSearchClient\Model\Facet
     */
    protected function generateTestingFacet(array $values): Facet
    {
        return (new Facet())
            ->setApiVersion($values['apiVersion'])
            ->setCount($values['count'])
            ->setDatabase($values['database'])
            ->setRawName($values['rawName'])
            ->setName($values['name'])
            ->setValues(array_map(
                [$this, 'generateTestingFacetValue'],
                $values['values']
            ));
    }
}
