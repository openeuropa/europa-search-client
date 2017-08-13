<?php

/**
 * @file
 * Contains EC\EuropaWS\Messages\MessageInterface.
 */

namespace EC\EuropaWS\Messages;

/**
 * Interface IdentifiableMessageInterface.
 *
 * It extends MessageInterface to allow objects to convey an identifier
 * used by the system and the targeted web service.
 *
 * @package EC\EuropaWS\Messages
 */
interface IdentifiableMessageInterface extends MessageInterface
{

    /**
     * Gets the identifier that is used between the
     * @return mixed
     */
    public function getMessageIdentifier();
}
