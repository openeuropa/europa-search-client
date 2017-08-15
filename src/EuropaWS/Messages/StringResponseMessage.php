<?php

/**
 * @file
 * Contains EC\EuropaWS\Messages\StringResponseMessage.
 */

namespace EC\EuropaWS\Messages;

/**
 * Class StringResponseMessage.
 *
 * It represents a response coming from the web service under the
 * format of string.
 *
 * @package EC\EuropaWS\Messages
 */
class StringResponseMessage implements MessageInterface
{

    /**
     * The string conveyed by the message.
     *
     * @var string
     */
    private $returnedString;

    /**
     * StringResponseMessage constructor.
     *
     * @param string $returnedString
     *   The string returned as response.
     */
    public function __construct($returnedString)
    {
        $this->returnedString = $returnedString;
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
