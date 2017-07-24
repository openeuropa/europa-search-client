<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Transmission\GuzzleTransmitter.
 */

namespace EC\EuropaSearch\Index\Transmission;

/**
 * Class GuzzleTransmitter
 *
 * It implements TransmitterInterface with the Guzzle library.
 *
 * @package EC\EuropaSearch\Index\Transmission
 */
class GuzzleTransmitter implements TransmitterInterface
{

    private $serviceConfiguration;

    /**
     * GuzzleTransmitter constructor.
     *
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
