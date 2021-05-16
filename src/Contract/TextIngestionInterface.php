<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface TextIngestionInterface extends IngestionInterface
{
    /**
     * @param string|null $text
     * @return $this
     */
    public function setText(?string $text): self;

    /**
     * @return string|null
     */
    public function getText(): ?string;
}
