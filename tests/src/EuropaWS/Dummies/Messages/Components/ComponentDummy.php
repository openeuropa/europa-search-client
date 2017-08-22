<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\Messages\Components\ComponentDummy.
 */

namespace EC\EuropaWS\Tests\Dummies\Messages\Components;

use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Proxies\BasicProxyController;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class ComponentDummy.
 *
 * ComponentInterface implementation for unit tests.
 *
 * @package EC\EuropaWS\Tests\Dummies\Clients
 */
class ComponentDummy implements ComponentInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return BasicProxyController::COMPONENT_ID_PREFIX.'componentDummy';
    }

    /**
     * {@inheritdoc}
     *
     * @internal Not implemented; used only for some unit tests.
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }
}
