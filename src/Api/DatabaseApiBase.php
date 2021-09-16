<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base api class for endpoints that work with a specific database.
 */
abstract class DatabaseApiBase extends ApiBase
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
