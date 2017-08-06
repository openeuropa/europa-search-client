<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\SearchServiceContainer
 */

namespace EC\EuropaSearch\Search;

use EC\EuropaSearch\Search\Communication\DynamicSchemaCommunication;
use EC\EuropaSearch\Search\Transmission\GuzzleTransmitter;
use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Search\Transmission\MockTransmitter;
use Pimple\Container;
use Symfony\Component\Validator\ValidatorBuilder;

/**
 * Class SearchServiceContainer.
 *
 * Pimple container for managing call to Communication and transmission layer
 * implementations.
 *
 * @package EC\EuropaSearch\Search
 */
class SearchServiceContainer extends Container
{

    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->container = new Container();
        $this->container['service_configuration'] = $serviceConfiguration;

        $this->container['validator'] = $this->container->factory(function ($c) {
            return (new ValidatorBuilder())->addMethodMapping('getConstraints')->getValidator();
        });

        $this->container['communicator'] = function ($c) {
            return new DynamicSchemaCommunication($c['service_configuration']);
        };

        // Choose the right transmitter to use according to the configuration.
        $this->container['transmitter'] = function ($c) {
            return new GuzzleTransmitter($c['service_configuration']);
        };
        if ($serviceConfiguration->isMockService()) {
            $this->container['transmitter'] = function ($c) {
                return new MockTransmitter($c['service_configuration']);
            };
        }
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
