<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use Symfony\Component\OptionsResolver\Options;

/**
 * Class representing the Ingestion API endpoints.
 */
class IngestionApi extends ApiBase
{

    /**
     * Ingest the provided text content.
     *
     * @param string $uri
     *   Link associated with document.
     * @param array $parameters
     *   The request parameters:
     *   - text: Text content to ingest.
     *   - language: Array of languages in ISO 639-1 format. Defaults to all
     *   languages.
     *   - metadata: Extra fields to index.
     *   - reference: The reference of the document. If left empty a random one
     *   will be generated.
     *   - aclUsers: List of authorized ECAS users in json format.
     *   - aclNoUsers: List of prohibited  ECAS users in json format.
     *   - aclGroups: List of authorized groups in json format.
     *   - aclNoGroups: List of prohibited groups in json format.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Ingestion
     *   The ingestion model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function ingestText(string $uri, array $parameters): Ingestion
    {
        $parameters['uri'] = $uri;
        $resolver = $this->getOptionResolver();

        $resolver->setDefined('text')
            ->setAllowedTypes('text', 'string');

        return $this->ingest('/rest/ingestion/text', $parameters, $resolver);
    }

    /**
     * Ingest the provided text content.
     *
     * @param string $uri
     *   Link associated with document.
     * @param array $parameters
     *   The request parameters:
     *   - file: Binary of new or updated document.
     *   - language: Array of languages in ISO 639-1 format. Defaults to all
     *   languages.
     *   - metadata: Extra fields to index.
     *   - reference: The reference of the document. If left empty a random one
     *   will be generated.
     *   - aclUsers: List of authorized ECAS users in json format.
     *   - aclNoUsers: List of prohibited  ECAS users in json format.
     *   - aclGroups: List of authorized groups in json format.
     *   - aclNoGroups: List of prohibited groups in json format.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Ingestion
     *   The ingestion model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function ingestFile(string $uri, array $parameters): Ingestion
    {
        $parameters['uri'] = $uri;
        $resolver = $this->getOptionResolver();

        $resolver->setDefined('file')
            ->setAllowedTypes('file', 'string');

        return $this->ingest('/rest/ingestion', $parameters, $resolver);
    }

    /**
     * Ingest the provided content.
     *
     * @param string $uri
     *   The REST uri.
     * @param array $parameters
     *   The request parameters:
     *   - file: Binary of new or updated document.
     *   - language: Array of languages in ISO 639-1 format. Defaults to all
     *   languages.
     *   - metadata: Extra fields to index.
     *   - reference: The reference of the document. If left empty a random one
     *   will be generated.
     *   - aclUsers: List of authorized ECAS users in json format.
     *   - aclNoUsers: List of prohibited  ECAS users in json format.
     *   - aclGroups: List of authorized groups in json format.
     *   - aclNoGroups: List of prohibited groups in json format.
     * @param \Symfony\Component\OptionsResolver\Options $resolver
     *   The options resolver.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Ingestion
     *   The ingestion model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    protected function ingest(string $uri, array $parameters, Options $resolver): Ingestion
    {
        $resolver->setRequired('uri')
            ->setAllowedTypes('uri', 'string')
            ->setAllowedValues('uri', function ($value) {
                return filter_var($value, FILTER_VALIDATE_URL);
            });

        $resolver->setDefined('language')
            ->setAllowedTypes('language', 'string[]');
        // @todo Validate languages with ISO 639-1 language codes.
        // Multiple languages possible in valid json format : [\"en\",\"fr\",\"de\",\"nl\"].
        // $resolver->setAllowedValues('language', []);

        $resolver->setDefined('reference')
            ->setAllowedTypes('reference', 'string');

        $resolver->setDefined('metadata');
        $resolver->setAllowedTypes('metadata', 'OpenEuropa\EuropaSearchClient\Model\MetadataCollection');

        $resolver->setDefined('aclUsers');
        $resolver->setAllowedTypes('aclUsers', 'array');

        $resolver->setDefined('aclNoUsers');
        $resolver->setAllowedTypes('aclNoUsers', 'array');

        $resolver->setDefined('aclGroups');
        $resolver->setAllowedTypes('aclGroups', 'array');

        $resolver->setDefined('aclNoGroups');
        $resolver->setAllowedTypes('aclNoGroups', 'array');

        $parameters = $resolver->resolve($parameters);

        // Process values.
        if (isset($parameters['metadata'])) {
            $parameters['metadata'] = json_encode($parameters['metadata']);
        }

        if (isset($parameters['aclUsers'])) {
            $parameters['aclUsers'] = json_encode($parameters['aclUsers']);
        }

        if (isset($parameters['aclNoUsers'])) {
            $parameters['aclNoUsers'] = json_encode($parameters['aclNoUsers']);
        }
        if (isset($parameters['aclGroups'])) {
            $parameters['aclGroups'] = json_encode($parameters['aclGroups']);
        }

        if (isset($parameters['aclNoGroups'])) {
            $parameters['aclNoGroups'] = json_encode($parameters['aclNoGroups']);
        }

        // Build the request.
        $queryKeys = array_flip(['apiKey', 'database', 'uri', 'reference']);
        $queryParameters = array_intersect_key($parameters, $queryKeys);
        $bodyParameters = array_diff_key($parameters, $queryKeys);
        $response = $this->send('POST', $uri, $queryParameters, $bodyParameters, true);

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
    public function deleteDocument(string $reference): bool
    {
        $parameters = [
            'reference' => $reference,
        ];

        $resolver = $this->getOptionResolver();

        $resolver->setRequired('reference')
            ->setAllowedTypes('reference', 'string')
            ->setAllowedValues('reference', function ($value) {
                if (!empty($value)) {
                    return true;
                }
            });

        $parameters = $resolver->resolve($parameters);

        $response = $this->send('DELETE', '/rest/document', $parameters);

        return $response->getStatusCode() === 200;
    }

    /**
     * Set the authorization token for ingestion.
     *
     * @param string $token
     *   The authorization token.
     *
     * @return $this
     */
    public function setToken(string $token): IngestionApi
    {
        $this->setRequestHeader('Authorization', "Bearer $token");
        $this->setRequestHeader('Authorization-propagation', "$token");

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getOptionResolver(): Options
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
        $base_path = $this->client->getConfiguration('ingestion_api_endpoint');
        $uri = rtrim($base_path, '/') . '/' . ltrim($path, '/');

        return $this->addQueryParameters($uri, $queryParameters);
    }
}
