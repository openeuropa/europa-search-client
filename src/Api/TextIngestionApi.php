<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Text ingestion API.
 */
class TextIngestionApi extends IngestionApiBase
{
    protected $text;
    protected $configuration = [];
    protected $languages = [];
    protected $optionResolver;

    public function __construct(OptionsResolver $optionResolver, array $configuration)
    {
        $this->optionResolver = $optionResolver;
        $this->setConfiguration($configuration);
    }


    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return $this->validateConfiguration();
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

    /** @todo Review */
    private function validateConfiguration(): array
    {
        return [
            'apiKey' => $this->requiredString(),
            'database' => $this->requiredString(),
            'textIngestionApiEndpoint' => [
                'type' => 'string',
                'required' => true,
                'value' => function (string $value) {
                    return filter_var($value, FILTER_VALIDATE_URL);
                },
            ],
            'consumerKey' => $this->requiredString(),
            'consumerSecret' => $this->requiredString(),
            'tokenApiEndpoint' => [
                'type' => 'string',
                'required' => true,
                'value' => function (string $value) {
                    return filter_var($value, FILTER_VALIDATE_URL);
                },
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    private function requiredString(): array
    {
        return [
            'type' => 'string',
            'required' => true,
        ];
    }
}
