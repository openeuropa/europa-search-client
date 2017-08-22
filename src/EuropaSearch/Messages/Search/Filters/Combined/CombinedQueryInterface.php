<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Combined\CombinedQueryInterface.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Combined;

use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Interface CombinedQueryInterface.
 *
 * Implementing this interface allows object to be recognized to be part of a
 * Search query.
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Combined
 */
interface CombinedQueryInterface extends ComponentInterface
{

    /**
     * Gets component children.
     *
     * @return array
     *   The components contained by the current component.
     */
    public function getChildComponents();
}
