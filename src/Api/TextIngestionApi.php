<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use OpenEuropa\EuropaSearchClient\Contract\TextIngestionApiInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Text ingestion API.
 */
class TextIngestionApi extends IngestionApiBase implements TextIngestionApiInterface
{
    /**
     * @var string|null
     */
    protected $text;

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'textIngestionApiEndpoint' => $this->getEndpointSchema(),
        ] + parent::getConfigSchema();
    }

    /**
     * @inheritDoc
     */
    public function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($text = $this->getText()) {
            $parts['text'] = [
                'content' => $text,
                'contentType' => 'text/plain',
            ];
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('textIngestionApiEndpoint');
    }

    /**
     * @inheritDoc
     */
    public function setText(?string $text): TextIngestionApiInterface
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getText(): ?string
    {
        return $this->text;
    }
}
