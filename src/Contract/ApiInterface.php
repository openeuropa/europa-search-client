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

    /**
     * @param array $configuration
     *
     * @return $this
     */
    public function setConfiguration(array $configuration): self;

    /**
     * @param OptionsResolver $optionsResolver
     *
     * @return $this
     */
    public function setOptionsResolver(OptionsResolver $optionsResolver): self;

    /**
     * @param ClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient): self;

    /**
     * @param RequestFactoryInterface $requestFactory
     *
     * @return $this
     */
    public function setRequestFactory(RequestFactoryInterface $requestFactory): self;

    /**
     * @param StreamFactoryInterface $streamFactory
     *
     * @return $this
     */
    public function setStreamFactory(StreamFactoryInterface $streamFactory): self;

    /**
     * @param UriFactoryInterface $uriFactory
     *
     * @return $this
     */
    public function setUriFactory(UriFactoryInterface $uriFactory): self;

    /**
     * @param MultipartStreamBuilder $multipartStreamBuilder
     *
     * @return $this
     */
    public function setMultipartStreamBuilder(MultipartStreamBuilder $multipartStreamBuilder): self;

    /**
     * @param SerializerInterface $serializer
     *
     * @return $this
     */
    public function setSerializer(SerializerInterface $serializer): self;

    /**
     * @param EncoderInterface $jsonEncoder
     *
     * @return $this
     */
    public function setJsonEncoder(EncoderInterface $jsonEncoder): self;
}
