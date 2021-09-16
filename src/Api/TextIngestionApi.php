<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\TextIngestionApiInterface;

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
