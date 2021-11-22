<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base class for endpoints that work with a specific database.
 */
abstract class DatabaseEndpointBase extends EndpointBase
{
    /**
     * @inheritDoc
     */
    protected function getConfigurationResolver(): OptionsResolver
    {
        $resolver = parent::getConfigurationResolver();

        $resolver->setRequired('apiKey')
            ->setAllowedTypes('apiKey', 'string');
        $resolver->setRequired('database')
            ->setAllowedTypes('database', 'string');

        return $resolver;
    }
}
