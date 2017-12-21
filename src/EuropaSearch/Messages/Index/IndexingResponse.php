<?php

namespace EC\EuropaSearch\Messages\Index;

use EC\EuropaSearch\Messages\MessageInterface;

/**
 * Class StringResponseMessage.
 *
 * It represents a response coming from the web service under the
 * format of string.
 *
 * @package EC\EuropaSearch\Messages
 */
class IndexingResponse implements MessageInterface
{
    /**
     * The string conveyed by the message.
     *
     * @var string
     */
    protected $returnedString;

    /**
     * The trackingId conveyed by the message.
     *
     * @var string
     */
    protected $trackingId;

    /**
     * StringResponseMessage constructor.
     *
     * @param string $returnedString
     *   The string returned as response.
     * @param string $trackingId
     *   The Europa Search tracking ids returned with response.
     */
    public function __construct($returnedString, $trackingId = null)
    {
        $this->returnedString = $returnedString;
        $this->trackingId = $trackingId;
    }


    /**
     * Gets the returned string.
     *
     * @return string
     *   The string returned as response.
     */
    public function getReturnedString()
    {
        return $this->returnedString;
    }

    /**
     * Gets the tracking id value received with the response.
     *
     * @return string
     *   The received tracking id.
     */
    public function getTrackingId()
    {
        return $this->trackingId;
    }

    /**
     * Sets the returned string.
     *
     * @param string $returnedString
     *   The string returned as response.
     */
    public function setReturnedString($returnedString)
    {
        $this->returnedString = $returnedString;
    }
}
