<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;
use Psr\Http\Message\UriInterface;

class DeleteEndpoint extends DatabaseEndpointBase implements TokenAwareInterface
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
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }
}
