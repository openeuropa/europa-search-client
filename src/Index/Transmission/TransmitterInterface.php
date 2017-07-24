<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Transmission\TransmitterInterface.
 */

namespace EC\EuropaSearch\Index\Transmission;

/**
 * Interface TransmitterInterface.
 *
 * It declares the contract for all classes that are in charge of sending
 * indexing requests to Europa search services.
 *
 * @package EC\EuropaSearch\Index\Transmission
 */
interface TransmitterInterface
{

    /**
     * Transmits to EuropaSearch service a request to index a web content.
     *
     * @param IndexingRequest $indexingRequest
     *   The object with data related to the web content to index.
     * @return string
     *   The reference of the web content if the request is successful;
     *   otherwise an exception is raised.
     * @throws TransmissionException
     *   Thrown in case of a transmission failure.
     */
    public function transmitWebContentRequest(IndexingRequest $indexingRequest);

    /**
     * Transmits to EuropaSearch service a request to index a file.
     *
     * @param IndexingRequest $indexingRequest
     *   The object with data related to the file to index.
     * @return string
     *   The reference of the file if the request is successful;
     *   otherwise an exception is raised.
     * @throws TransmissionException
     *   Thrown in case of a transmission failure.
     */
    public function transmitFileRequest(IndexingRequest $indexingRequest);

    /**
     * Transmits to EuropaSearch service a request to index a file.
     *
     * @param IndexingRequest $indexingRequest
     *   The object with data related to the index reference to delete.
     * @return string
     *   The reference of the deleted index item if the request is successful;
     *   otherwise an exception is raised.
     * @throws TransmissionException
     *   Thrown in case of a transmission failure.
     */
    public function transmitIndexDeleteRequest(IndexingRequest $indexingRequest);
}
