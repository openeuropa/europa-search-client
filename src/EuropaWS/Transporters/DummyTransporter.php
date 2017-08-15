<?php

/**
 * @file
 * Contains EC\EuropaWS\Transporters\DummyTransporter.
 */

namespace EC\EuropaWS\Transporters;

use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Messages\RequestInterface;

/**
 * Class DummyTransporter.
 *
 * Dummy class; waiting for an actual implementation.
 *
 * @todo To remove once the transporter layer is implemented.
 *
 * @package EC\EuropaWS\Transporters
 */
class DummyTransporter implements TransporterInterface
{
    private $WSConfiguration;

    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request)
    {
        return 'Request received but I am a dumb transporter; I receive request but I do nothing else.';
    }

    /**
     * {@inheritDoc}
     */
    public function setWSConfiguration(WSConfigurationInterface $configuration)
    {
        $this->WSConfiguration = $configuration;
    }
}
