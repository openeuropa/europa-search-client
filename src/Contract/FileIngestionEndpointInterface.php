<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface FileIngestionEndpointInterface extends IngestionEndpointInterface
{
    /**
     * @param string|null $file
     * @return $this
     */
    public function setFile(?string $file): self;

    /**
     * @return string|null
     */
    public function getFile(): ?string;
}
