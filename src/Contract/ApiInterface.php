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
