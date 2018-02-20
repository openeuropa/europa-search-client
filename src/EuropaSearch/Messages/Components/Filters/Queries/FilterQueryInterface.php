<?php

namespace OpenEuropa\EuropaSearch\Messages\Search\Filters\Queries;

use OpenEuropa\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Interface FilterQueryInterface.
 *
 * Implementing this interface allows object to be recognized to be part of a
 * Search query.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Search\Filters\Queries
 */
interface FilterQueryInterface extends ComponentInterface
{
    /**
     * Gets component children.
     *
     * @return array
     *   The components contained by the current component.
     */
    public function getChildComponents();
}
