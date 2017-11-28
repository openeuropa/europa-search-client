<?php

namespace EC\EuropaSearch\Tests\Proxies\Converters\Index;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Index\DeleteIndexItemMessage;
use EC\EuropaSearch\Messages\Index\IndexWebContentMessage;
use EC\EuropaSearch\Messages\Index\IndexFileMessage;
use EC\EuropaSearch\Transporters\Requests\Index\DeleteIndexItemRequest;
use EC\EuropaSearch\Transporters\Requests\Index\IndexWebContentRequest;
use EC\EuropaSearch\Transporters\Requests\Index\IndexFileRequest;

/**
 * Class ConverterDataProvider.
 *
 * Provides data for the proxy layer used for web content indexing
 * related tests.
 *
 * @package EC\EuropaSearch\Tests\Proxies\Converters\Index
 */
class ConverterDataProvider
{

    /**
     * Provides objects necessary for the test.
     *
     * @return array
     *   The objects for the test:
     *   - 'submitted': IndexedDocument to convert in the test;
     *   - 'expected' : Excepted IndexingRequest at the end of the test;
     */
    public function indexedDocumentProvider()
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

        // Expected object.
        $indexingRequest = new IndexWebContentRequest();
        $indexingRequest->setAPIKey('a221108a-180d-HTTP-INDEXING-TEST');
        $indexingRequest->setDatabase('EC-EUROPA-DUMMY-INDEXING');
        $indexingRequest->setDocumentId($documentId);
        $indexingRequest->setDocumentURI($documentURI);
        $indexingRequest->setDocumentLanguage($documentLanguage);

        $fileContent = json_decode(file_get_contents(__DIR__.'/fixtures/json_sample.json'));
        $indexingRequest->setMetadataJSON(json_encode($fileContent));

        $indexingRequest->setDocumentContent('

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempor mattis sem vitae egestas. Nulla sed mauris sed ante convallis scelerisque. Vestibulum urna nisl, aliquam non risus vel, varius commodo augue. Aliquam efficitur elementum dapibus. Aliquam erat volutpat. Nulla orci purus, ultricies non velit at, venenatis fringilla ipsum. Sed porta nunc sit amet felis semper, at tempor erat dapibus. Sed id ipsum enim. Mauris suscipit pharetra lacinia. In nisi sem, tincidunt ac vestibulum ut, ultrices sed nisi. Phasellus nec diam at libero suscipit consequat. Nunc dapibus, ante ac hendrerit varius, sapien ex consequat ante, non venenatis ipsum metus eu ligula. Phasellus mattis arcu ut erat vulputate, sit amet blandit magna egestas. Vivamus nisl ipsum, maximus non tempor nec, finibus eu nisl. Phasellus lacinia interdum iaculis.
\n

Duis pellentesque, risus id efficitur convallis, elit justo sollicitudin elit, in convallis urna est id nibh. Sed rhoncus est nec leo hendrerit, ut tempus urna feugiat. Ut sed tempor orci, eu euismod massa. Phasellus condimentum sollicitudin ante, vel pretium mauris auctor quis. Etiam sit amet consectetur lorem. Phasellus at massa ex. Fusce porta est sit amet arcu pretium, ut suscipit eros molestie. Fusce malesuada ornare cursus. Curabitur sit amet eros nibh. Sed imperdiet magna quis odio tempus vehicula. Praesent auctor porta dolor, eu lacinia ante venenatis vel.
\n

In diam tellus, sagittis sit amet finibus eget, ultrices sed turpis. Proin sodales dictum elit eget mollis. Aliquam nec laoreet purus. Pellentesque accumsan arcu vitae ipsum euismod, nec faucibus tellus rhoncus. Sed lacinia at augue vitae hendrerit. Aliquam egestas ante sit amet erat dignissim, non dictum ligula iaculis. Nulla tempor nec metus vitae pellentesque. Nulla porta sit amet lacus eu porttitor.
\n

Nam consectetur leo eu felis vehicula sollicitudin. Aliquam pharetra, nulla quis tempor malesuada, odio nunc accumsan dui, in feugiat turpis ipsum vel tortor. Praesent auctor at justo convallis convallis. Aenean fringilla magna leo, et dictum nisi molestie sed. Quisque non ornare sem. Duis quis felis erat. Praesent rutrum vehicula orci ac suscipit.
\n

Sed nec eros sit amet lorem convallis accumsan sed nec tellus. Maecenas eu odio dapibus, mollis leo eget, interdum urna. Phasellus ac dui commodo, cursus lorem nec, condimentum erat. Pellentesque eget imperdiet nisl, at convallis enim. Sed feugiat fermentum leo ac auctor. Aliquam imperdiet enim ac pellentesque commodo. Mauris sed sapien eu nulla mattis hendrerit ac ac mauris. Donec gravida, nisi sit amet rhoncus volutpat, quam nisl ullamcorper nisl, in luctus sapien justo et ex. Fusce dignissim felis felis, tempus faucibus tellus pulvinar vitae. Proin gravida tempus eros sit amet viverra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum libero quis tellus commodo, non vestibulum lacus rutrum. Etiam euismod odio ipsum, nec pulvinar nisl ultrices sit amet. Nunc feugiat orci vel odio interdum, non dignissim erat hendrerit. Vestibulum gravida et elit nec placerat.
');

        return [
            'submitted' => $indexedDocument,
            'expected' => $indexingRequest,
        ];
    }



    /**
     * Provides objects necessary for the test.
     *
     * @return array
     *   The objects for the test:
     *   - 'submitted': IndexedDocument to convert in the test;
     *   - 'expected' : Excepted IndexingRequest at the end of the test;
     */
    public function indexedFileProvider()
    {
        $fileId = 'web_content_client_1';
        $fileURI = 'http://europa.test.com/file.pdf';
        $fileLanguage = 'fr';

        // Submitted object.
        $indexedFile = new IndexFileMessage();
        $indexedFile->setDocumentURI($fileURI);
        $indexedFile->setDocumentFile(__DIR__.'/fixtures/file.pdf');
        $indexedFile->setDocumentId($fileId);
        $indexedFile->setDocumentLanguage($fileLanguage);

        $metadata = new FullTextMetadata('title');
        $metadata->setValues(['this the title']);
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

        // Expected object.
        $indexingRequest = new IndexFileRequest();
        $indexingRequest->setAPIKey('a221108a-180d-HTTP-INDEXING-TEST');
        $indexingRequest->setDatabase('EC-EUROPA-DUMMY-INDEXING');
        $indexingRequest->setDocumentId($fileId);
        $indexingRequest->setDocumentURI($fileURI);
        $indexingRequest->setDocumentLanguage($fileLanguage);

        $fileContent = json_decode(file_get_contents(__DIR__.'/fixtures/json_sample.json'));
        $indexingRequest->setMetadataJSON(json_encode($fileContent));

        $indexingRequest->setDocumentFile(__DIR__.'/fixtures/file.pdf');

        return [
          'submitted' => $indexedFile,
          'expected' => $indexingRequest,
        ];
    }

    /**
     * Provides objects necessary for the test.
     *
     * @return array
     *   The objects for the test:
     *   - 'submitted': IndexedDocument to convert in the test;
     *   - 'expected' : Excepted IndexingRequest at the end of the test;
     */
    public function indexedItemDeletionProvider()
    {
        $documentId = 'web_content_client_1';

        // Submitted object.
        $indexedDocument = new DeleteIndexItemMessage();
        $indexedDocument->setDocumentId($documentId);

        // Expected object.
        $indexingRequest = new DeleteIndexItemRequest();
        $indexingRequest->setAPIKey('a221108a-180d-HTTP-INDEXING-TEST');
        $indexingRequest->setDatabase('EC-EUROPA-DUMMY-INDEXING');
        $indexingRequest->setDocumentId($documentId);

        return [
            'submitted' => $indexedDocument,
            'expected' => $indexingRequest,
        ];
    }
}
