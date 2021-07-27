<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\TextIngestionApiInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    protected function setConfiguration(array $configuration): void
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired('apiKey')
            ->setAllowedTypes('apiKey', 'string')
            ->setDefault('apiKey', $configuration['apiKey']);
        $optionsResolver->setRequired('database')
            ->setAllowedTypes('database', 'string')
            ->setDefault('database', $configuration['database']);
        $optionsResolver->setRequired('textIngestionApiEndpoint')
            ->setAllowedTypes('textIngestionApiEndpoint', 'string')
            ->setDefault('textIngestionApiEndpoint', $configuration['textIngestionApiEndpoint'])
            ->setAllowedValues('textIngestionApiEndpoint', function (string $value) {
                return filter_var($value, FILTER_VALIDATE_URL);
            });
        $this->configuration = $optionsResolver->resolve($configuration);
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
