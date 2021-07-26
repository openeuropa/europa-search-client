<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

interface ApiInterface
{
    /**
     * @return array[]
     *   Associative array describing the configuration schema of a particular
     *   API service. The keys are configuration names, each value is an
     *   associative array having the following keys:
     *   - type: String or array of strings with the type/types of this config,
     *     according to \Symfony\Component\OptionsResolver\OptionsResolver.
     *   - required: (optional) Boolean indicating that this configuration is
     *     mandatory. If missed, the configuration is optional.
     *   - default: (optional) Default value when the configuration is missing.
     *   - value: (optional) List of allowed values or a callable.
     *   Example:
     *   @code
     *   [
     *       'apiKey' => [
     *           'type' => 'string',
     *           'required' => true,
     *       ],
     *       'otherConfig => [
     *           'type' => ['integer', 'string'],
     *           'required' => false,
     *           'default' => 0,
     *       ],
     *   ]
     *   @endcode
     *
     * @see \Symfony\Component\OptionsResolver\OptionsResolver
     */
    public function getConfigSchema(): array;
}
