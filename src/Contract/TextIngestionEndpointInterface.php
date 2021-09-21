<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface TextIngestionEndpointInterface extends IngestionEndpointInterface
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
