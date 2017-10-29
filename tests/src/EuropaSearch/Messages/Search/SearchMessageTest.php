<?php

namespace EC\EuropaSearch\Messages\Search;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use EC\EuropaSearch\Tests\Messages\Components\Filters\Queries\BooleanQueryDataProvider;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SearchMessageTest.
 *
 * Tests the validation process on SearchMessage.
 *
 * @package EC\EuropaSearch\Messages\Search
 */
class SearchMessageTest extends AbstractEuropaSearchTest
{
    /**
     * Test the SearchMessage validation for a web content (success case).
     */
    public function testSearchMessageValidationSuccess()
    {
        $search = new SearchMessage();
        $search->setSearchedLanguages(['en', 'fr']);
        $search->setHighLightParameters('<strong>{}</strong>', 250);
        $search->setPagination(1, 20);
        $search->setSearchedText('text to search');
        $sortMetadata = new DateMetadata('field');
        $search->setSortCriteria($sortMetadata, 'DESC');
        $search->setSessionToken('123456');

        $booleanProvider = new BooleanQueryDataProvider();
        $searchQuery = $booleanProvider->getValidNestedBooleanQuery();
        $search->setQuery($searchQuery);

        $validationErrors = $this->getDefaultValidator()->validate($search);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'SearchMessage validation constraints are not well defined.');
    }

    /**
     * Test the SearchMessage validation for a web content (failure case).
     */
    public function testSearchMessageValidationFailure()
    {
        $search = new SearchMessage();
        $search->setSearchedLanguages(['en', 'zorglub']);
        $search->setHighLightParameters(23, '250');
        $search->setPagination(1.9, '18');
        $sortMetadata = new DateMetadata('field');
        $search->setSortCriteria($sortMetadata, 'no rule');
        $search->setSessionToken(true);

        $booleanProvider = new BooleanQueryDataProvider();
        $searchQuery = $booleanProvider->getShouldInvalidNestedBooleanQuery();
        $search->setQuery($searchQuery);

        $validationErrors = $this->getDefaultValidator()->validate($search);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/searchmessage_violations.yml'));
        $expected = $parsedData['expectedViolations']['SearchMessage'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'SearchMessage validation constraints are not well defined for: '.$name);
        }
    }
}
