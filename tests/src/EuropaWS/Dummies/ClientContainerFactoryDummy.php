<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\ClientContainerFactoryDummy.
 */

namespace EC\EuropaWS\Tests\Dummies;

use EC\EuropaWS\ClientContainerFactory;

/**
 * Class ClientContainerDummy.
 *
 * A ClientContainerFactory extension for unit tests.
 *
 * @package EC\EuropaWS\Tests\Dummies
 */
class ClientContainerFactoryDummy extends ClientContainerFactory
{

    /**
     * ClientContainer constructor.
     */
    public function __construct()
    {
        $this->configRepoPath = __DIR__.'/test_config';
    }
}
