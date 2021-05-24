<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\DeleteApiInterface;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;

class DeleteApi extends ApiBase implements DeleteApiInterface
{
    use TokenAwareTrait;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'deleteApiEndpoint' => $this->getEndpointSchema(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function deleteDocument(): bool
    {
        $response = $this->send('DELETE');
        return $response->getStatusCode() === 200;
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('deleteApiEndpoint');
    }

    /**
     * @inheritDoc
     */
    public function setReference(string $reference): DeleteApiInterface
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getReference(): string
    {
        return $this->reference;
    }
}
