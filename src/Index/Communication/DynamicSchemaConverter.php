<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Communication\DynamicSchemaConverter.
 */

namespace EC\EuropaSearch\Index\Communication;

/**
 * Class DynamicSchemaConverter
 *
 * It implements ConverterInterface.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Index\Communication
 */
class DynamicSchemaConverter implements ConverterInterface
{

    private $serviceConfiguration;

    /**
     * DynamicSchemaConverter constructor.
     * @param ServiceConfiguration $serviceConfiguration
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->serviceConfiguration = $serviceConfiguration;
    }
}
