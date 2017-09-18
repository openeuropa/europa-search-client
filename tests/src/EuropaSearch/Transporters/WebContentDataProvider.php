<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Transporters\Index\WebContentDataProvider.
 */

namespace EC\EuropaSearch\Tests\Transporters;

use EC\EuropaSearch\Messages\Index\WebContentRequest;
use EC\EuropaSearch\Messages\Search\SearchRequest;

/**
 * Class WebContentDataProvider.
 *
 * Provides data for the transporter layer used for web content indexing
 * related tests.
 *
 * @package EC\EuropaSearch\Tests\Transporters
 */
class WebContentDataProvider
{

    /**
     * Provides objects necessary for the test.
     *
     * @return IndexingRequest
     *   The objects for the test.
     */
    public function webContentIndexingRequestProvider()
    {

        $documentId = 'web_content_1';
        $documentURI = 'http://europa.test.com/content.html';
        $documentLanguage = 'fr';
        $documentContent = '

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempor mattis sem vitae egestas. Nulla sed mauris sed ante convallis scelerisque. Vestibulum urna nisl, aliquam non risus vel, varius commodo augue. Aliquam efficitur elementum dapibus. Aliquam erat volutpat. Nulla orci purus, ultricies non velit at, venenatis fringilla ipsum. Sed porta nunc sit amet felis semper, at tempor erat dapibus. Sed id ipsum enim. Mauris suscipit pharetra lacinia. In nisi sem, tincidunt ac vestibulum ut, ultrices sed nisi. Phasellus nec diam at libero suscipit consequat. Nunc dapibus, ante ac hendrerit varius, sapien ex consequat ante, non venenatis ipsum metus eu ligula. Phasellus mattis arcu ut erat vulputate, sit amet blandit magna egestas. Vivamus nisl ipsum, maximus non tempor nec, finibus eu nisl. Phasellus lacinia interdum iaculis.
\n

Duis pellentesque, risus id efficitur convallis, elit justo sollicitudin elit, in convallis urna est id nibh. Sed rhoncus est nec leo hendrerit, ut tempus urna feugiat. Ut sed tempor orci, eu euismod massa. Phasellus condimentum sollicitudin ante, vel pretium mauris auctor quis. Etiam sit amet consectetur lorem. Phasellus at massa ex. Fusce porta est sit amet arcu pretium, ut suscipit eros molestie. Fusce malesuada ornare cursus. Curabitur sit amet eros nibh. Sed imperdiet magna quis odio tempus vehicula. Praesent auctor porta dolor, eu lacinia ante venenatis vel.
\n

In diam tellus, sagittis sit amet finibus eget, ultrices sed turpis. Proin sodales dictum elit eget mollis. Aliquam nec laoreet purus. Pellentesque accumsan arcu vitae ipsum euismod, nec faucibus tellus rhoncus. Sed lacinia at augue vitae hendrerit. Aliquam egestas ante sit amet erat dignissim, non dictum ligula iaculis. Nulla tempor nec metus vitae pellentesque. Nulla porta sit amet lacus eu porttitor.
\n

Nam consectetur leo eu felis vehicula sollicitudin. Aliquam pharetra, nulla quis tempor malesuada, odio nunc accumsan dui, in feugiat turpis ipsum vel tortor. Praesent auctor at justo convallis convallis. Aenean fringilla magna leo, et dictum nisi molestie sed. Quisque non ornare sem. Duis quis felis erat. Praesent rutrum vehicula orci ac suscipit.
\n

Sed nec eros sit amet lorem convallis accumsan sed nec tellus. Maecenas eu odio dapibus, mollis leo eget, interdum urna. Phasellus ac dui commodo, cursus lorem nec, condimentum erat. Pellentesque eget imperdiet nisl, at convallis enim. Sed feugiat fermentum leo ac auctor. Aliquam imperdiet enim ac pellentesque commodo. Mauris sed sapien eu nulla mattis hendrerit ac ac mauris. Donec gravida, nisi sit amet rhoncus volutpat, quam nisl ullamcorper nisl, in luctus sapien justo et ex. Fusce dignissim felis felis, tempus faucibus tellus pulvinar vitae. Proin gravida tempus eros sit amet viverra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum libero quis tellus commodo, non vestibulum lacus rutrum. Etiam euismod odio ipsum, nec pulvinar nisl ultrices sit amet. Nunc feugiat orci vel odio interdum, non dignissim erat hendrerit. Vestibulum gravida et elit nec placerat.
';

        // Submitted object.
        $indexingRequest = new WebContentRequest();
        $indexingRequest->setAPIKey('a221108a-180d-HTTP-CLIENT-LIBRARY-TEST');
        $indexingRequest->setDatabase('EC-EUROPA-DUMMY');
        $indexingRequest->setDocumentId($documentId);
        $indexingRequest->setDocumentURI($documentURI);
        $indexingRequest->setDocumentLanguage($documentLanguage);

        $fileContent = file_get_contents(__DIR__.'/fixtures/indexing_json_sample.json');
        $indexingRequest->setMetadataJSON($fileContent);

        $indexingRequest->setDocumentContent($documentContent);

        return $indexingRequest;
    }

    /**
     * Provides objects necessary for the test.
     *
     * @return SearchRequest
     *   The objects for the test.
     */
    public function searchRequestProvider()
    {

        $searchRequest = new SearchRequest();
        $searchRequest->setLanguages(['en', 'fr']);
        $searchRequest->setHighlightRegex('<strong>{}</strong>');
        $searchRequest->setHighlightLimit(250);
        $searchRequest->setPageSize(20);
        $searchRequest->setPageNumber(1);
        $searchRequest->setText('text to search');
        $searchRequest->setSort('field:DESC');
        $searchRequest->setSessionToken('123456');
        $searchRequest->setAPIKey('a221108a-180d-HTTP-CLIENT-LIBRARY-TEST');

        $fileContent = file_get_contents(__DIR__.'/fixtures/search_json_sample.json');

        $searchRequest->setQueryJSON($fileContent);

        return $searchRequest;
    }
}
