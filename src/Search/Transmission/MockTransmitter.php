<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Transmission\MockTransmitter.
 */

namespace EC\EuropaSearch\Search\Transmission;

/**
 * Class MockTransmitter
 *
 * It implements TransmitterInterface with a Mock.
 *
 * @package EC\EuropaSearch\Search\Transmission
 */
class MockTransmitter implements TransmitterInterface
{

    private $serviceConfiguration;

    /**
     * MockTransmitter constructor.
     * @param ServiceConfiguration $serviceConfiguration
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->serviceConfiguration = $serviceConfiguration;
    }

    /**
     * @inheritDoc
     *
     * @param IndexingRequest $indexingRequest
     *   The formatted document sent to the Europa search service.
     */
    public function transmitWebContentRequest(IndexingRequest $indexingRequest)
    {
        // TODO: Implement transmitWebContentRequest() method.
    }

    /**
     * @inheritDoc
     *
     * @param IndexingRequest $indexingRequest
     *   The formatted document sent to the Europa search service.
     */
    public function transmitFileRequest(IndexingRequest $indexingRequest)
    {
        // TODO: Implement transmitFileRequest() method.
    }

    /**
     * @inheritDoc
     *
     * @param IndexingRequest $indexingRequest
     *   The formatted document sent to the Europa search service.
     */
    public function transmitIndexDeleteRequest(IndexingRequest $indexingRequest)
    {
        // TODO: Implement transmitIndexDeleteRequest() method.
    }
}