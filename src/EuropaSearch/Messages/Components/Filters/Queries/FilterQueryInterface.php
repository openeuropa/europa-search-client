<?php

namespace EC\EuropaSearch\Messages\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Interface FilterQueryInterface.
 *
 * Implementing this interface allows object to be recognized to be part of a
 * Search query.
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Queries
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
