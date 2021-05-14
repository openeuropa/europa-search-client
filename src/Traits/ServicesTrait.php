<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Traits;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\SearchInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

trait ServicesTrait
{
    /**
     * @return array
     */
    protected function getConfiguration(): array
    {
        if (!isset($this->configuration)) {
            $this->configuration = $this->getOptionResolver()
                ->resolve($this->getContainer()->get('config'));
        }
        return $this->configuration;
    }

    /**
     * @return \OpenEuropa\EuropaSearchClient\Contract\SearchInterface
     */
    protected function getSearch(): SearchInterface
    {
        return $this->getContainer()->get('searchApi');
    }

    /**
     * @return \Psr\Http\Client\ClientInterface
     */
    protected function getHttpClient(): ClientInterface
    {
        return $this->getContainer()->get('httpClient');
    }

    /**
     * @return \Psr\Http\Message\RequestFactoryInterface
     */
    protected function getRequestFactory(): RequestFactoryInterface
    {
        return $this->getContainer()->get('requestFactory');
    }

    /**
     * @return \Psr\Http\Message\StreamFactoryInterface
     */
    protected function getStreamFactory(): StreamFactoryInterface
    {
        return $this->getContainer()->get('streamFactory');
    }

    /**
     * @return \Psr\Http\Message\UriFactoryInterface
     */
    protected function getUriFactory(): UriFactoryInterface
    {
        return $this->getContainer()->get('uriFactory');
    }

    /**
     * @return \Http\Message\MultipartStream\MultipartStreamBuilder
     */
    protected function getMultipartStreamBuilder(): MultipartStreamBuilder
    {
        return $this->getContainer()->get('multipartStreamBuilder');
    }

    /**
     * @return \Symfony\Component\Serializer\SerializerInterface
     */
    protected function getSerializer(): SerializerInterface
    {
        return $this->getContainer()->get('serializer');
    }

    /**
     * @return \Symfony\Component\Serializer\Encoder\JsonEncoder
     */
    protected function getJsonEncoder(): JsonEncoder
    {
        return $this->getContainer()->get('jsonEncoder');
    }

    /**
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected function getOptionResolver(): OptionsResolver
    {
        return $this->getContainer()->get('optionResolver');
    }
}
