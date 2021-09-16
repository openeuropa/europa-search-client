<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\DeleteApiInterface;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;
use Psr\Http\Message\UriInterface;

class DeleteApi extends DatabaseApiBase implements DeleteApiInterface
{
    use TokenAwareTrait;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        return $this->send('DELETE')->getStatusCode() === 200;
    }

    /**
     * @inheritDoc
     */
    protected function getRequestUriQuery(UriInterface $uri): array
    {
        return [
            'apiKey' => $this->getConfigValue('apiKey'),
            'database' => $this->getConfigValue('database'),
            'reference' => $this->getReference(),
        ] + parent::getRequestUriQuery($uri);
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
