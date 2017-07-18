<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\IndexServiceContainer
 */

namespace EC\EuropaSearch\Index;

use EC\EuropaSearch\Index\Communication\DynamicSchemaConverter;
use EC\EuropaSearch\Index\Transmission\GuzzleTransmitter;
use Pimple\Container;

/**
 * Class IndexServiceContainer.
 *
 * Pimple container for managing call to Communication and transmission layer
 * implementations.
 *
 * @package EC\EuropaSearch\Index
 */
class IndexServiceContainer extends Container
{

    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->container['service_configuration'] = $serviceConfiguration;

        $this->container = new Container();

        $this->container['validator'] = $this->container->factory(function ($c) {
            return (new ValidatorBuilder())->addMethodMapping('getConstraints')->getValidator();
        });

        $this->container['converter'] = function ($c) {
            return new DynamicSchemaConverter($c['service_configuration']);
        };

        $this->container['transmitter'] = function ($c) {
            return new GuzzleTransmitter($c['service_configuration']);
        };
    }

    /**
     * Return service object or parameter value.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->container[$name];
    }
}
