<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\EuropaSearchDummy.
 */

namespace EC\EuropaSearch\Tests;

use EC\EuropaSearch\EuropaSearch;

/**
 * Class EuropaSearchDummy.
 *
 * Extension designed for processing the unit tests with a specific config.
 *
 * @package EC\EuropaSearch\Tests
 */
class EuropaSearchDummy extends EuropaSearch
{

    /**
     * EuropaSearchDummy constructor.
     */
    public function __construct()
    {
        $this->configRepoPath = __DIR__.'/config';
    }
}
