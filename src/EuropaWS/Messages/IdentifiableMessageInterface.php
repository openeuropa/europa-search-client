<?php

/**
 * @file
 * Contains EC\EuropaWS\Messages\ValidatableMessageInterface.
 */

namespace EC\EuropaWS\Messages;

/**
 * Interface IdentifiableMessageInterface.
 *
 * It extends ValidatableMessageInterface to allow objects to convey an identifier
 * used by the system and the targeted web service.
 *
 * @package EC\EuropaWS\Messages
 */
interface IdentifiableMessageInterface extends ValidatableMessageInterface
{

    /**
     * Gets the identifier that is used between the
     * @return mixed
     */
    public function getMessageIdentifier();
}