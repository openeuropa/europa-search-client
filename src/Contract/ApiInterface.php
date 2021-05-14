<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use League\Container\ContainerAwareInterface;

interface ApiInterface extends ContainerAwareInterface
{
    /**
     * Ensures that the passed configuration is sane.
     *
     * @return void
     */
    public function buildConfigurationSchema(): void;
}
