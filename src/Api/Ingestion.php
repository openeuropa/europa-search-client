<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\IngestionInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenInterface;
use OpenEuropa\EuropaSearchClient\Model\IngestionResult;

/**
 * Ingestion API.
 */
class Ingestion extends ApiBase implements IngestionInterface
{
    /**
     * @var TokenInterface
     */
    protected $token;

    /**
     * @inheritDoc
     */
    public function setToken(TokenInterface $token): IngestionInterface
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Ingest the provided text content.
     *
     * @param array $parameters
     *   The request parameters:
     *   - uri: Link associated with document. Required.
     *   - text: Content to ingest.
     *   - language: Array of languages in ISO 639-1 format. Defaults to all
     *   languages.
     *   - metadata: Extra fields to index.
     *   - reference: The reference of the document. If left empty a random one
     *   will be generated.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\IngestionResult
     *   The ingestion model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function ingestText(array $parameters): IngestionResult
    {
        $resolver = $this->optionResolver;

        $resolver->setRequired('uri')
            ->setAllowedTypes('uri', 'string')
            ->setAllowedValues(
                'uri',
                function ($value) {
                    return filter_var($value, FILTER_VALIDATE_URL);
                }
            );

        $resolver->setDefined('text')
            ->setAllowedTypes('text', 'string');

        $resolver->setDefined('language')
            ->setAllowedTypes('language', 'string[]');
        // @todo Validate languages with ISO 639-1 language codes.
        // $resolver->setAllowedValues('languages', []);

        $resolver->setDefined('metadata');
        // @todo Metadata is a complex structure and it requires its own type.
        // $resolver->setAllowedTypes('metadata', 'array');
        // $resolver->setAllowedValues('metadata', '');

        $resolver->setDefined('reference')
            ->setAllowedTypes('reference', 'string');

        $parameters = $resolver->resolve($parameters);

        // Build the request.
        $queryKeys = array_flip(['apiKey', 'database', 'uri', 'reference']);
        $queryParameters = array_intersect_key($parameters, $queryKeys);
        $bodyParameters = array_diff_key($parameters, $queryKeys);
        $response = $this->send(
            'POST',
            'rest/ingestion/text',
            $queryParameters,
            $bodyParameters,
            true
        );

        /** @var IngestionResult $ingestion */
        $ingestion = $this->serializer->deserialize(
            (string)$response->getBody(),
            IngestionResult::class,
            'json'
        );

        return $ingestion;
    }

    /**
     * Delete a single document.
     *
     * @param string $reference
     *   The document reference.
     *
     * @return bool
     *   True if the item was successfully delete.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function deleteDocument(string $reference)
    {
        $resolver = $this->optionResolver;
        $parameters = $resolver->resolve([]);
        $parameters['reference'] = $reference;

        $response = $this->send('DELETE', 'rest/ingestion', $parameters);

        return $response->getStatusCode()===200;
    }


    /**
     * @inheritDoc
     */
    protected function getRequestUri(): string
    {
        // TODO: Implement getUri() method.
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        // TODO: Implement getEndpointUri() method.
    }

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [];
    }
}
