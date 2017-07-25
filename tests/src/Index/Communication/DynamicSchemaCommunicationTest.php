<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Index\Communication\DynamicSchemaCommunication;
 */

namespace EC\EuropaSearch\Tests\Index\Communication;

use EC\EuropaSearch\Index\Client\DocumentMetadata;
use EC\EuropaSearch\Index\Client\IndexedDocument;
use EC\EuropaSearch\Index\Communication\DynamicSchemaCommunication;
use EC\EuropaSearch\Index\Transmission\IndexingRequest;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class DynamicSchemaCommunicationTest
 * @package EC\EuropaSearch\Tests\Index\Communication
 */
class DynamicSchemaCommunicationTest extends AbstractTest
{
    /**
     * Test a conversion of an IndexedDocument object in a IndexingRequest one.
     */
    public function testConvertSentObjectSuccess()
    {
        $data = $this->indexedDocumentProvider();
        $submitted = $data['submitted'];
        $expected = $data['expected'];
        $configuration = $data['service'];
        $communication = new DynamicSchemaCommunication($configuration);

        $indexingRequest = $communication->convertSentObject($submitted);

        $expected = json_encode($expected);
        $returned = json_encode($indexingRequest);

        $this->assertJsonStringEqualsJsonString($expected, $returned);
    }

    /**
     * Provides objects necessary for the test.
     *
     * @return array
     *   The objects for the test:
     *   - 'submitted':  IndexedDocument to convert in the test;
     *   - 'expected' :   Excepted IndexingRequest at the end of the test;
     *   - 'service'  :  ServiceConfiguration used in the test,
     */
    protected function indexedDocumentProvider()
    {
        $service = $this->getServiceConfigurationDummy();
        $documentId = 'reference/web_content/1';
        $documentURI = 'http://europa.test.com/content.html';
        $documentLanguage = 'fr';

        // Submitted object.
        $indexedDocument = new IndexedDocument();
        $indexedDocument->setDocumentURI($documentURI);
        $indexedDocument->setDocumentType(IndexedDocument::WEB_CONTENT);
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
        $metadataList = array();
        $metadataList[] = new DocumentMetadata('title', 'this the title', 'fulltext');
        $metadataList[] = new DocumentMetadata('tag', 'taxonomy term', 'string');
        $metadataList[] = new DocumentMetadata('rank', 1, 'integer');
        $metadataList[] = new DocumentMetadata('percentage', 0.1, 'float');
        $metadataList[] = new DocumentMetadata('publishing_date', date('F j, Y, g:i a', strtotime('11-12-2018')), 'date');
        $metadataList[] = new DocumentMetadata('uri', 'http://www.europa.com', 'uri');
        $indexedDocument->setMetadata($metadataList);

        // Expected object.
        $indexingRequest = new IndexingRequest(
            $service->getApiKey(),
            $service->getDatabase(),
            $documentId
        );
        $indexingRequest->setUri($documentURI);
        $indexingRequest->setLanguage($documentLanguage);
        $indexingRequest->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempor mattis sem vitae egestas. Nulla sed mauris sed ante convallis scelerisque. Vestibulum urna nisl, aliquam non risus vel, varius commodo augue. Aliquam efficitur elementum dapibus. Aliquam erat volutpat. Nulla orci purus, ultricies non velit at, venenatis fringilla ipsum. Sed porta nunc sit amet felis semper, at tempor erat dapibus. Sed id ipsum enim. Mauris suscipit pharetra lacinia. In nisi sem, tincidunt ac vestibulum ut, ultrices sed nisi. Phasellus nec diam at libero suscipit consequat. Nunc dapibus, ante ac hendrerit varius, sapien ex consequat ante, non venenatis ipsum metus eu ligula. Phasellus mattis arcu ut erat vulputate, sit amet blandit magna egestas. Vivamus nisl ipsum, maximus non tempor nec, finibus eu nisl. Phasellus lacinia interdum iaculis.
\n

Duis pellentesque, risus id efficitur convallis, elit justo sollicitudin elit, in convallis urna est id nibh. Sed rhoncus est nec leo hendrerit, ut tempus urna feugiat. Ut sed tempor orci, eu euismod massa. Phasellus condimentum sollicitudin ante, vel pretium mauris auctor quis. Etiam sit amet consectetur lorem. Phasellus at massa ex. Fusce porta est sit amet arcu pretium, ut suscipit eros molestie. Fusce malesuada ornare cursus. Curabitur sit amet eros nibh. Sed imperdiet magna quis odio tempus vehicula. Praesent auctor porta dolor, eu lacinia ante venenatis vel.
\n

In diam tellus, sagittis sit amet finibus eget, ultrices sed turpis. Proin sodales dictum elit eget mollis. Aliquam nec laoreet purus. Pellentesque accumsan arcu vitae ipsum euismod, nec faucibus tellus rhoncus. Sed lacinia at augue vitae hendrerit. Aliquam egestas ante sit amet erat dignissim, non dictum ligula iaculis. Nulla tempor nec metus vitae pellentesque. Nulla porta sit amet lacus eu porttitor.
\n

Nam consectetur leo eu felis vehicula sollicitudin. Aliquam pharetra, nulla quis tempor malesuada, odio nunc accumsan dui, in feugiat turpis ipsum vel tortor. Praesent auctor at justo convallis convallis. Aenean fringilla magna leo, et dictum nisi molestie sed. Quisque non ornare sem. Duis quis felis erat. Praesent rutrum vehicula orci ac suscipit.
\n

Sed nec eros sit amet lorem convallis accumsan sed nec tellus. Maecenas eu odio dapibus, mollis leo eget, interdum urna. Phasellus ac dui commodo, cursus lorem nec, condimentum erat. Pellentesque eget imperdiet nisl, at convallis enim. Sed feugiat fermentum leo ac auctor. Aliquam imperdiet enim ac pellentesque commodo. Mauris sed sapien eu nulla mattis hendrerit ac ac mauris. Donec gravida, nisi sit amet rhoncus volutpat, quam nisl ullamcorper nisl, in luctus sapien justo et ex. Fusce dignissim felis felis, tempus faucibus tellus pulvinar vitae. Proin gravida tempus eros sit amet viverra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum libero quis tellus commodo, non vestibulum lacus rutrum. Etiam euismod odio ipsum, nec pulvinar nisl ultrices sit amet. Nunc feugiat orci vel odio interdum, non dignissim erat hendrerit. Vestibulum gravida et elit nec placerat.');

        $indexingRequest->setIsFile(false);
        $indexingRequest->setMetadataJSON('{"esIN_title":"this the title","esST_tag":"taxonomy term","esNU_rank":1,"esNU_percentage":0.1,"esDA_publishing_date":"2018-12-11T00:00:00+0100","esST_uri":"http:\/\/www.europa.com"}');

        return array(
            'submitted' => $indexedDocument,
            'expected' => $indexingRequest,
            'service' => $service,
        );
    }
}
