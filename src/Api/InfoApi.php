<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\InfoApiInterface;
use OpenEuropa\EuropaSearchClient\Model\Info;

class InfoApi extends ApiBase implements InfoApiInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'infoApiEndpoint' => $this->getEndpointSchema(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('infoApiEndpoint');
    }

    /**
     * @inheritDoc
     */
    public function execute(): Info
    {
        /** @var Info $info */
        $info = $this->serializer->deserialize(
            $this->send('GET')->getBody()->__toString(),
            Info::class,
            'json'
        );
        return $info;
    }
}
