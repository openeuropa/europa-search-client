<?php

namespace EC\EuropaSearch\Tests\Applications;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Index\DeleteIndexItemMessage;
use EC\EuropaSearch\Messages\Index\IndexFileMessage;
use EC\EuropaSearch\Messages\Index\IndexWebContentMessage;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\RangeClause;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\TermClause;
use EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery;
use EC\EuropaSearch\Messages\Search\SearchMessage;
use EC\EuropaSearch\Messages\Search\SearchResponse;
use EC\EuropaSearch\Messages\Search\SearchResult;

/**
 * Class ApplicationDataProvider.
 *
 * Provides data for the Application layer used for indexing and searching related
 * tests.
 *
 * @package EC\EuropaSearch\Tests\Applications
 */
class ApplicationDataProvider
{

    /**
     * Provides objects necessary for the index adding test.
     *
     * @return \EC\EuropaSearch\Messages\Index\IndexWebContentMessage
     *   The objects for the test.
     */
    public function getWebContentMessageTestData()
    {
        $documentId = 'web_content_client_1';
        $documentURI = 'http://europa.test.com/content.html';
        $documentLanguage = 'fr';

        // Submitted object.
        $indexedDocument = new IndexWebContentMessage();
        $indexedDocument->setDocumentURI($documentURI);
        $indexedDocument->setDocumentContent('<div id="lipsum">
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempor mattis sem vitae egestas. Nulla sed mauris sed ante convallis scelerisque. Vestibulum urna nisl, aliquam non risus vel, varius commodo augue. Aliquam efficitur elementum dapibus. Aliquam erat volutpat. Nulla orci purus, ultricies non velit at, venenatis fringilla ipsum. Sed porta nunc sit amet felis semper, at tempor erat dapibus. Sed id ipsum enim. Mauris suscipit pharetra lacinia. In nisi sem, tincidunt ac vestibulum ut, ultrices sed nisi. Phasellus nec diam at libero suscipit consequat. Nunc dapibus, ante ac hendrerit varius, sapien ex consequat ante, non venenatis ipsum metus eu ligula. Phasellus mattis arcu ut erat vulputate, sit amet blandit magna egestas. Vivamus nisl ipsum, maximus non tempor nec, finibus eu nisl. Phasellus lacinia interdum iaculis.
</p>\n
<p>
Duis pellentesque, risus id efficitur convallis, elit justo sollicitudin elit, in convallis urna est id nibh. Sed rhoncus est nec leo hendrerit, ut tempus urna feugiat. Ut sed tempor orci, eu euismod massa. Phasellus condimentum sollicitudin ante, vel pretium mauris auctor quis. Etiam sit amet consectetur lorem. Phasellus at massa ex. Fusce porta est sit amet arcu pretium, ut suscipit eros molestie. Fusce malesuada ornare cursus. Curabitur sit amet eros nibh. Sed imperdiet magna quis odio tempus vehicula. Praesent auctor porta dolor, eu lacinia ante venenatis vel.
</p>\n
<p>
In diam tellus, sagittis sit amet finibus eget, ultrices sed turpis. Proin sodales dictum elit eget mollis. Aliquam nec laoreet purus. Pellentesque accumsan arcu vitae ipsum euismod, nec faucibus tellus rhoncus. Sed lacinia at augue vitae hendrerit. Aliquam egestas ante sit amet erat dignissim, non dictum ligula iaculis. Nulla tempor nec metus vitae pellentesque. Nulla porta sit amet lacus eu porttitor.
</p>\n
<p>
Nam consectetur leo eu felis vehicula sollicitudin. Aliquam pharetra, nulla quis tempor malesuada, odio nunc accumsan dui, in feugiat turpis ipsum vel tortor. Praesent auctor at justo convallis convallis. Aenean fringilla magna leo, et dictum nisi molestie sed. Quisque non ornare sem. Duis quis felis erat. Praesent rutrum vehicula orci ac suscipit.
</p>\n
<p>
Sed nec eros sit amet lorem convallis accumsan sed nec tellus. Maecenas eu odio dapibus, mollis leo eget, interdum urna. Phasellus ac dui commodo, cursus lorem nec, condimentum erat. Pellentesque eget imperdiet nisl, at convallis enim. Sed feugiat fermentum leo ac auctor. Aliquam imperdiet enim ac pellentesque commodo. Mauris sed sapien eu nulla mattis hendrerit ac ac mauris. Donec gravida, nisi sit amet rhoncus volutpat, quam nisl ullamcorper nisl, in luctus sapien justo et ex. Fusce dignissim felis felis, tempus faucibus tellus pulvinar vitae. Proin gravida tempus eros sit amet viverra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum libero quis tellus commodo, non vestibulum lacus rutrum. Etiam euismod odio ipsum, nec pulvinar nisl ultrices sit amet. Nunc feugiat orci vel odio interdum, non dignissim erat hendrerit. Vestibulum gravida et elit nec placerat.
</p></div>');
        $indexedDocument->setDocumentId($documentId);
        $indexedDocument->setDocumentLanguage($documentLanguage);

        $metadata = new FullTextMetadata('title');
        $metadata->setValues(['this the title']);
        $indexedDocument->addMetadata($metadata);

        $metadata = new StringMetadata('tag');
        $metadata->setValues(['taxonomy term']);
        $indexedDocument->addMetadata($metadata);

        $metadata = new IntegerMetadata('rank');
        $metadata->setValues([1]);
        $indexedDocument->addMetadata($metadata);

        $metadata = new FloatMetadata('percentage');
        $metadata->setValues([0.1]);
        $indexedDocument->addMetadata($metadata);
        $metadata = new DateMetadata('publishing_date');
        $metadata->setValues([date('F j, Y, g:i a', strtotime('11-12-2018'))]);
        $indexedDocument->addMetadata($metadata);

        $metadata = new URLMetadata('uri');
        $metadata->setValues(['http://www.europa.com']);
        $indexedDocument->addMetadata($metadata);

        return $indexedDocument;
    }

    /**
     * Provides objects necessary for the file index adding test.
     *
     * @return \EC\EuropaSearch\Messages\Index\IndexWebContentMessage
     *   The objects for the test.
     */
    public function getFileMessageTestData()
    {
        $documentId = 'file_client_1';
        $documentURI = 'http://europa.test.com/file/file.pdf';
        $documentLanguage = 'fr';

        // Submitted object.
        $indexedFile = new IndexFileMessage();
        $indexedFile->setDocumentURI($documentURI);
        $indexedFile->setDocumentFile(__DIR__.'/fixtures/file.pdf');
        $indexedFile->setDocumentId($documentId);
        $indexedFile->setDocumentLanguage($documentLanguage);

        $metadata = new FullTextMetadata('title');
        $metadata->setValues(['this the file title']);
        $indexedFile->addMetadata($metadata);

        $metadata = new StringMetadata('tag');
        $metadata->setValues(['taxonomy term']);
        $indexedFile->addMetadata($metadata);

        $metadata = new IntegerMetadata('rank');
        $metadata->setValues([1]);
        $indexedFile->addMetadata($metadata);

        $metadata = new FloatMetadata('percentage');
        $metadata->setValues([0.1]);
        $indexedFile->addMetadata($metadata);
        $metadata = new DateMetadata('publishing_date');
        $metadata->setValues([date('F j, Y, g:i a', strtotime('11-12-2018'))]);
        $indexedFile->addMetadata($metadata);

        $metadata = new URLMetadata('uri');
        $metadata->setValues(['http://www.europa.com']);
        $indexedFile->addMetadata($metadata);

        return $indexedFile;
    }

    /**
     * Provides objects necessary for a test of index item deletion.
     *
     * @return \EC\EuropaSearch\Messages\Index\DeleteIndexItemMessage
     *   The message object to use in the deletion test.
     */
    public function getDeleteIndexItemTestData()
    {
        $deletingMessage = new DeleteIndexItemMessage();
        $deletingMessage->setDocumentId('web_content_delete_1');

        return $deletingMessage;
    }

    /**
     * Provides objects necessary for the test on Search message.
     *
     * @return array
     *   The objects for the test:
     *   - 'submitted':  \EC\EuropaSearch\Messages\Search\SearchMessage to send in the test;
     *   - 'expected' :  Excepted \EC\EuropaSearch\Messages\Search\SearchResponse at the end of the test.
     */
    public function getSearchMessageTestData()
    {
        // Define search message to send.
        $searchMessage = new SearchMessage();
        $searchMessage->setSearchedLanguages(['fr']);
        $searchMessage->setHighLightParameters('<strong>{}</strong>', 250);
        $searchMessage->setPagination(20, 1);
        $searchMessage->setSearchedText('Lorem ipsum');

        $searchQuery = $this->getBooleanQueryTestData();
        $searchMessage->setQuery($searchQuery);


        // Define the receive SearchResponse object
        $searchResponse = new SearchResponse();
        $searchResponse->setSearchedTerms('ipsum');
        $searchResponse->setPageNumber(1);
        $searchResponse->setPageSize(10);
        $searchResponse->setTotalResults(3);
        $searchResponse->setResultSorting('relevance');
        $searchResponse->setLanguage('en');
        $searchResponse->setLanguageProbability(0);
        $this->setWebContentResultsTestData($searchResponse);

        return [
            'submitted' => $searchMessage,
            'expected' => $searchResponse,
        ];
    }

    /**
     * Gets the web content references for the test SearchResponse object.
     *
     * @param \EC\EuropaSearch\Messages\Search\SearchResponse $searchResponse
     *   The object in which inject the references.
     */
    private function setWebContentResultsTestData(SearchResponse $searchResponse)
    {
        foreach ([1, 2, 3] as $resultId) {
            $result = new SearchResult();
            $result->setResultReference('web_content_'.$resultId);
            $result->setActualURL('http://test.europa.eu/web_content_'.$resultId);

            $url = 'https://intragate.acceptance.ec.europa.eu/es/search/url/click?data=token%d==';
            $url = sprintf($url, $resultId);
            $result->setEuropaSearchURL($url);

            $result->setContentType('text/plain');

            $result->setDatabase('EC-EUROPA-DUMMY-SEARCH');
            $result->setDatabaseLabel('EC-EUROPA-DUMMY-SEARCH');

            $content = $resultId.': Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempor mattis sem vitae egestas. Nulla sed mauris sed ante convallis scelerisque. Vestibulum urna nisl, aliquam non risus vel, varius commodo augue. Aliquam efficitur elementum dapibus. Aliquam erat volutpat. Nulla orci purus, ultricies non velit at, venenatis fringilla ipsum. Sed porta nunc sit amet felis semper, at tempor erat dapibus. Sed id ipsum enim. Mauris suscipit pharetra lacinia. In nisi sem, tincidunt ac vestibulum ut, ultrices sed nisi. Phasellus nec diam at libero suscipit consequat. Nunc dapibus, ante ac hendrerit varius, sapien ex consequat ante, non venenatis ipsum metus eu ligula. Phasellus mattis arcu ut erat vulputate, sit amet blandit magna egestas. Vivamus nisl ipsum, maximus non tempor nec, finibus eu nisl. Phasellus lacinia interdum iaculis.';
            $summary = $content.' summary.';
            $result->setResultSummary($summary);
            $result->setResultFullContent($content.' content.');

            $weight = (int) ('6'.$resultId);
            $result->setSortingWeight($weight);
            $result->setIsAccessRestricted(false);

            $this->setResultMetadataTestData($result, $resultId);

            $searchResponse->addResult($result);
        }
    }

    /**
     * Sets the metadata of a SearchResult object used for a client test.
     *
     * @param \EC\EuropaSearch\Messages\Search\SearchResult $result
     *   SearchResult object to populate.
     * @param int                                           $resultId
     *   Id making metadata value unique in the test.
     */
    private function setResultMetadataTestData(SearchResult $result, $resultId)
    {
        $metadata = [
            'rank' => [$resultId],
            'ESST_FILENAME' => ['file.txt'],
            'ES_CONTENTTYPE' => ['text/plain'],
            'uri' => [sprintf('http://www.europa%d.com', $resultId)],
            'title' => ['this the title '.$resultId],
            'publishing_date' => [sprintf('201%d-0%d-2%dT00:00:00+00:00', $resultId, $resultId, $resultId)],
            'percentage' => ['0.'.$resultId],
            'tag' => ['taxonomy term '.$resultId],
        ];

        $result->setResultMetadata($metadata);
    }

    /**
     * Get a valid BooleanQuery for a client test.
     *
     * @return \EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery
     *   The valid BooleanQuery object to use in tests.
     */
    private function getBooleanQueryTestData()
    {
        $filters = [];

        $filter = new RangeClause(new IntegerMetadata('rank'));
        $filter->setLowerBoundaryIncluded(1);
        $filter->setUpperBoundaryIncluded(5);
        $filters[] = $filter;

        $filter = new TermClause(new FullTextMetadata('title'));
        $filter->setTestedValue('title');
        $filters[] = $filter;

        $booleanQuery = new BooleanQuery();
        foreach ($filters as $filter) {
            $booleanQuery->addMustFilterClause($filter);
        }

        return $booleanQuery;
    }
}
