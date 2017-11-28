<?php

namespace EC\EuropaSearch\Tests\Messages\Components\Filters\Clauses;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\FieldExistsClause;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\RangeClause;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\TermClause;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\TermsClause;

/**
 * Class FilterClauseDataProvider.
 *
 * Supplies a set of dataProvider containing simple filters for
 * CombinedQuery tests.
 *
 * @package EC\EuropaSearch\Tests\Messages\Components\Filters\Clauses
 */
class FilterClauseDataProvider
{

    /**
     * Gets valid filterClause objects.
     *
     * @return array
     *   Array of filterClause object.
     */
    public function getValidFilters()
    {
        $returnedFilters = [];

        $rangeFilter = new RangeClause(new IntegerMetadata('test_data1'));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $returnedFilters[] = $rangeFilter;

        $rangeFilter = new RangeClause(new DateMetadata('test_data4'));
        $rangeFilter->setLowerBoundaryExcluded('30-07-2018');
        $returnedFilters[] = $rangeFilter;

        $returnedFilters[] = new FieldExistsClause(new DateMetadata('test_data4'));
        $returnedFilters[] = new FieldExistsClause(new FloatMetadata('test_data5'));

        $validTermList = $this->getValidTermList();
        $returnedFilters = array_merge($returnedFilters, $validTermList);

        $validTermsList = $this->getValidTermsList();
        $returnedFilters = array_merge($returnedFilters, $validTermsList);

        return $returnedFilters;
    }

    /**
     * Gets invalid filterClause objects.
     *
     * @return array
     *   Array of filterClause object.
     */
    public function getInValidFilters()
    {
        $returnedFilters = [];

        $rangeFilter = new RangeClause(new IntegerMetadata(1234));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $returnedFilters[] = $rangeFilter;

        $rangeFilter = new RangeClause(new DateMetadata('test_data5'));
        $rangeFilter->setLowerBoundaryExcluded('32-33-2018');
        $returnedFilters[] = $rangeFilter;

        $rangeFilter = new RangeClause(new FloatMetadata('test_data2'));
        $rangeFilter->setLowerBoundaryExcluded('blabla');
        $rangeFilter->setUpperBoundaryExcluded(5.0);
        $returnedFilters[] = $rangeFilter;

        $returnedFilters[] = new FieldExistsClause(new StringMetadata(1234));

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
        $filter = new TermClause($metadata);
        $filter->setTestedValue('test2');
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new TermClause($metadata);
        $filter->setTestedValue(2);
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new TermClause($metadata);
        $filter->setTestedValue(3.2);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new TermClause($metadata);
        $filter->setTestedValue('http://www.positive.com');
        $returnedValues[] = $filter;

        return $returnedValues;
    }

    /**
     * Gets valid TermsClause for testing.
     *
     * @return array
     *   The list of valid Values objects.
     */
    public function getValidTermsList()
    {
        $returnedValues = [];

        $metadata = new StringMetadata('test1');
        $filter = new TermsClause($metadata);
        $filter->setTestedValues(['test21', 'test22']);
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new TermsClause($metadata);
        $filter->setTestedValues([21, 22]);
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new TermsClause($metadata);
        $filter->setTestedValues([3.21, 3.22]);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new TermsClause($metadata);
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
        $filter = new TermClause($metadata);
        $filter->setTestedValue(362);
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new TermClause($metadata);
        $filter->setTestedValue('invalid');
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new TermClause($metadata);
        $filter->setTestedValue(-3);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new TermClause($metadata);
        $filter->setTestedValue('/url/com');
        $returnedValues[] = $filter;

        $metadata = new StringMetadata(5);
        $filter = new TermClause($metadata);
        $filter->setTestedValue('valid string');
        $returnedValues[] = $filter;

        $metadata = new BooleanMetadata('data6');
        $filter = new TermClause($metadata);
        $filter->setTestedValue(true);
        $returnedValues[] = $filter;

        return $returnedValues;
    }

    /**
     * Gets invalid Values for testing.
     *
     * @return array
     *   The list of invalid Values objects.
     */
    public function getInValidTermsList()
    {
        $returnedValues = [];

        $metadata = new StringMetadata('test1');
        $filter = new TermsClause($metadata);
        $filter->setTestedValues([234, 'test22']);
        $returnedValues[] = $filter;

        $metadata = new IntegerMetadata('test2');
        $filter = new TermsClause($metadata);
        $filter->setTestedValues([21, 'invalid 1']);
        $returnedValues[] = $filter;

        $metadata = new FloatMetadata('test3');
        $filter = new TermsClause($metadata);
        $filter->setTestedValues(['invalid 2', 3]);
        $returnedValues[] = $filter;

        $metadata = new URLMetadata('test4');
        $filter = new TermsClause($metadata);
        $urls = ['/url1.com', 342.3];
        $filter->setTestedValues($urls);
        $returnedValues[] = $filter;

        $metadata = new StringMetadata(5);
        $filter = new TermsClause($metadata);
        $filter->setTestedValues(['test11', 'test12']);
        $returnedValues[] = $filter;

        $metadata = new BooleanMetadata('data6');
        $filter = new TermsClause($metadata);
        $filter->setTestedValues([true, true]);
        $returnedValues[] = $filter;

        return $returnedValues;
    }
}
