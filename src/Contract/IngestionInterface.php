<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\IngestionResult;

interface IngestionInterface extends ApiInterface
{
    /**
     * @param TokenInterface $tokenService
     *
     * @return $this
     */
    public function setTokenService(TokenInterface $tokenService): self;

    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\IngestionResult
     */
    public function ingestText(): IngestionResult;

    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\IngestionResult
     */
    public function ingestFile(): IngestionResult;

    /**
     * @return bool
     */
    public function delete(): bool;
}
