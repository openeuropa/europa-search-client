<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use Http\Factory\Guzzle\UriFactory;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class representing the Ingestion API endpoints.
 *
 * @todo Enforce required parameters in the method signature.
 */
class IngestionApi extends ApiBase
{

    const SERVER_URL = "ingestionApiServer";

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
     * @return \OpenEuropa\EuropaSearchClient\Model\Ingestion
     *   The ingestion model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function ingestText(array $parameters): Ingestion
    {
        $resolver = $this->getOptionResolver();

        $resolver->setRequired('uri')
            ->setAllowedTypes('uri', 'string')
            ->setAllowedValues('uri', function ($value) {
                return filter_var($value, FILTER_VALIDATE_URL);
            });

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
        $response = $this->send('POST', 'rest/ingestion/text', $queryParameters, $bodyParameters, true);

        /** @var Ingestion $ingestion */
        $ingestion = $this->getSerializer()->deserialize((string)$response->getBody(), Ingestion::class, 'json');

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
        $resolver = $this->getOptionResolver();
        $parameters = $resolver->resolve([]);
        $parameters['reference'] = $reference;

        $response = $this->send('DELETE', 'rest/ingestion', $parameters);

        return $response->getStatusCode() === 200;
    }

    /**
     * @inheritDoc
     */
    protected function getOptionResolver(): OptionsResolver
    {
        $resolver = parent::getOptionResolver();

        $resolver->setRequired('apiKey')
            ->setAllowedTypes('apiKey', 'string')
            ->setDefault('apiKey', $this->client->getConfiguration('apiKey'));

        $resolver->setRequired('database')
            ->setAllowedTypes('database', 'string')
            ->setDefault('database', $this->client->getConfiguration('database'));

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    protected function prepareUri(string $path, array $queryParameters = []): string
    {
        $base_path = $this->client->getConfiguration(self::SERVER_URL);
        $uri = rtrim($base_path, '/') . '/' . ltrim($path, '/');

        return $this->addQueryParameters($uri, $queryParameters);
    }
}
