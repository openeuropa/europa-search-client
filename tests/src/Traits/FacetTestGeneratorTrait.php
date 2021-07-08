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
     * @return array
     *   An arrayed facet.
     */
    protected function generateTestingFacetValuesAsObject(array $facetValuesAsArray): array
    {
        $facetValuesAsObject = array_map(
            [$this, 'generateTestingFacetValue'],
            $facetValuesAsArray
        );

        return $facetValuesAsObject;
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
     *   An array with two items:
     *   0: A list of facet items as raw array.
     *   1: The same list as model object.
     */
    protected function generateTestingFacetItems(int $count): array
    {
        $facetsAsArray = [];
        for ($i = 0; $i < $count; $i++) {
            $toShuffle = md5(serialize($facetsAsArray));
            $facetValuesAsArray = $this->generateTestingFacetValuesAsArray(rand(2, 7));
            $facetsAsArray[] = [
                'apiVersion' => str_shuffle($toShuffle),
                'count' => rand(1, 100000),
                'database' => str_shuffle($toShuffle),
                'rawName' => str_shuffle($toShuffle),
                'name' => str_shuffle($toShuffle),
                'values' => $facetValuesAsArray,
            ];
        }

        $facetsAsObject = array_map(
            [$this, 'generateTestingFacet'],
            $facetsAsArray
        );

        return [$facetsAsArray, $facetsAsObject];
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
