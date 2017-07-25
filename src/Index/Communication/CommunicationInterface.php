<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Communication\ConverterInterface.
 */

namespace EC\EuropaSearch\Index\Communication;

use EC\EuropaSearch\Index\Client\IndexedDocument;

/**
 * Interface CommunicationInterface
 *
 * It declares the contract for all classes that are in charge of converting
 * indexing request objects submitted by the client into a format
 * understandable by the Europa Search services.
 *
 * @package EC\EuropaSearch\Index\Communication
 */
interface CommunicationInterface
{
    /**
     * Converts and communicates the document to index for transmission.
     *
     * @param IndexedDocument $indexedDocument
     *    The document to index.
     * @return int $responseCode
     *   The response code resulting from the communication.
     * @throws ConversionException
     *   In case the document conversion meets an exception.
     * @throws CommunicationException
     *   In case the document communication for transmission meets an
     *   exception.
     *
     */
    public function communicateContentIndexing(IndexedDocument $indexedDocument);

    /**
     * @param string $documentId
     *    The id of the document index to delete.
     * @return int $responseCode
     *   The response code resulting from the communication.
     * @throws ConversionException
     *   In case the document conversion meets an exception.
     * @throws CommunicationException
     *   In case the document communication for transmission meets an
     *   exception.
     */
    public function communicateIndexDelete($documentId);
}
