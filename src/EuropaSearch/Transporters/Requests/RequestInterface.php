<?php

namespace EC\EuropaSearch\Transporters\Requests;

/**
 * Class RequestInterface.
 *
 * Implementing it allows objects to instantiated web service request content.
 *
 * @package EC\EuropaSearch\Transporters\Requests
 */
interface RequestInterface
{

    /**
     * Add to the Request object the converted message components.
     *
     * @param array $components
     *   The list of converted components.
     */
    public function addConvertedComponents(array $components);
}
