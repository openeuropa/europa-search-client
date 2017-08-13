<?php
/**
 * Created by PhpStorm.
 * User: gillesdeudon
 * Date: 13/08/17
 * Time: 17:14
 */

namespace EC\EuropaWS\Tests\Dummies\Messages\Components;

use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Proxies\ProxyProvider;
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
    public function getProxyIdentifier()
    {
        return ProxyProvider::COMPONENT_ID_PREFIX.'componentDummy';
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
