<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Combined\SimpleFilterDataProvider.
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Combined;

use EC\EuropaSearch\Messages\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Search\Filters\Simple\FieldExists;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Range;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Term;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Terms;

/**
 * Class SimpleFilterDataProvider.
 *
 * Supplies a set of dataProvider containing simple filters for
 * CombinedQuery tests.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Combined
 */
class SimpleFilterDataProvider
{

    /**
     * Gets valid simple filter objects.
     *
     * @return array
     *   Array of simple filter object.
     */
    public function getValidFilters()
    {

        $returnedFilters = [];

        $rangeFilter = new Range(new IntegerMetadata('test_data1'));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $returnedFilters[] = $rangeFilter;

        $rangeFilter = new Range(new DateMetadata('test_data4'));
        $rangeFilter->setLowerBoundaryExcluded('30-07-2018');
        $returnedFilters[] = $rangeFilter;

        $returnedFilters[] = new FieldExists(new DateMetadata('test_data4'));
        $returnedFilters[] = new FieldExists(new FloatMetadata('test_data5'));

        $validTermList = $this->getValidTermList();
        $returnedFilters = array_merge($returnedFilters, $validTermList);

        $validTermsList = $this->getValidTermsList();
        $returnedFilters = array_merge($returnedFilters, $validTermsList);

        return $returnedFilters;
    }

    /**
     * Gets invalid simple filter objects.
     *
     * @return array
     *   Array of simple filter object.
     */
    public function getInValidFilters()
    {

        $returnedFilters = [];

        $rangeFilter = new Range(new IntegerMetadata(1234));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $returnedFilters[] = $rangeFilter;

        $rangeFilter = new Range(new DateMetadata('test_data5'));
        $rangeFilter->setLowerBoundaryExcluded('32-33-2018');
        $returnedFilters[] = $rangeFilter;

        $rangeFilter = new Range(new FloatMetadata('test_data2'));
        $rangeFilter->setLowerBoundaryExcluded('blabla');
        $rangeFilter->setUpperBoundaryExcluded(5.0);
        $returnedFilters[] = $rangeFilter;

        $returnedFilters[] = new FieldExists(new StringMetadata(1234));

        $invalidTermList = $this->getInValidTermList();
        $returnedFilters = array_merge($returnedFilters, $invalidTermList);

        $invalidTermsList = $this->getInValidTermsList();
        $returnedFilters = array_merge($returnedFilters, $invalidTermsList);

        return $returnedFilters;
    }

    /**
     * Gets valid Values for testing.
     *
     * @return array
     *   The list of valid Value objects.
     */
    public function getValidTermList()
    {

        $returnedValues = [];

        $metadata = new StringMetadata('test1');
        $filter = new Term($metadata);
        $filter->setTestedValue('test2');
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new Term($metadata);
        $filter->setTestedValue(2);
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new Term($metadata);
        $filter->setTestedValue(3.2);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new Term($metadata);
        $filter->setTestedValue('http://www.positive.com');
        $returnedValues[] = $filter;

        return $returnedValues;
    }

    /**
     * Gets valid Terms for testing.
     *
     * @return array
     *   The list of valid Values objects.
     */
    public function getValidTermsList()
    {

        $returnedValues = [];

        $metadata = new StringMetadata('test1');
        $filter = new Terms($metadata);
        $filter->setTestedValues(['test21', 'test22']);
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new Terms($metadata);
        $filter->setTestedValues([21, 22]);
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new Terms($metadata);
        $filter->setTestedValues([3.21, 3.22]);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new Terms($metadata);
        $urls = [
            'http://www.url1.com',
            'http://www.url2.com',
        ];
        $filter->setTestedValues($urls);
        $returnedValues[] = $filter;

        return $returnedValues;
    }

    /**
     * Gets invalid Values for testing.
     *
     * @return array
     *   The list of invalid Value objects.
     */
    public function getInValidTermList()
    {

        $returnedValues = [];

        $metadata = new StringMetadata('test1');
        $filter = new Term($metadata);
        $filter->setTestedValue(362);
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new Term($metadata);
        $filter->setTestedValue('invalid');
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new Term($metadata);
        $filter->setTestedValue(-3);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new Term($metadata);
        $filter->setTestedValue('/url/com');
        $returnedValues[] = $filter;

        $metadata = new StringMetadata(5);
        $filter = new Term($metadata);
        $filter->setTestedValue('valid string');
        $returnedValues[] = $filter;

        $metadata = new BooleanMetadata('data6');
        $filter = new Term($metadata);
        $filter->setTestedValue(true);
        $returnedValues[] = $filter;

        return $returnedValues;
    }

    /**
     * Gets invalid Valuess for testing.
     *
     * @return array
     *   The list of invalid Values objects.
     */
    public function getInValidTermsList()
    {

        $returnedValues = [];

        $metadata = new StringMetadata('test1');
        $filter = new Terms($metadata);
        $filter->setTestedValues([234, 'test22']);
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new Terms($metadata);
        $filter->setTestedValues([21, 'invalid 1']);
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new Terms($metadata);
        $filter->setTestedValues(['invalid 2', 3]);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new Terms($metadata);
        $urls = [
            '/url1.com',
            342.3,
        ];
        $filter->setTestedValues($urls);
        $returnedValues[] = $filter;

        $metadata = new StringMetadata(5);
        $filter = new Terms($metadata);
        $filter->setTestedValues(['test11', 'test12']);
        $returnedValues[] = $filter;

        $metadata = new BooleanMetadata('data6');
        $filter = new Terms($metadata);
        $filter->setTestedValues([true, true]);
        $returnedValues[] = $filter;

        return $returnedValues;
    }
}
