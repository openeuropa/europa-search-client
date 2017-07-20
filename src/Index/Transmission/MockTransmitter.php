<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Transmission\MockTransmitter.
 */

namespace EC\EuropaSearch\Index\Transmission;

/**
 * Class MockTransmitter
 *
 * It implements TransmitterInterface with a Mock.
 *
 * @package EC\EuropaSearch\Index\Transmission
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
}
