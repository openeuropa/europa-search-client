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
     * @param ServiceConfiguration $serviceConfiguration
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->serviceConfiguration = $serviceConfiguration;
    }
}
