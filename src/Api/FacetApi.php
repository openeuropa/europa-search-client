<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Model\FacetCollection;
use OpenEuropa\EuropaSearchClient\Model\FacetValue;
use Symfony\Component\OptionsResolver\Options;

/**
 * Class representing the Facet API endpoints.
 */
class FacetApi extends ApiBase
{
    /**
     * Executes a facet search.
     *
     * @param array $parameters
     *   The request parameters:
     *     - text: The text to match in documents. Required.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\FacetValue
     *   The facet model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function query(array $parameters = []): FacetValue
    {
        $resolver = $this->getOptionResolver();

        $resolver->setRequired('text')
            ->setAllowedTypes('text', 'string')
            ->setDefault('text', '***');

        $resolver->setDefined('displayLanguage')
            ->setAllowedTypes('displayLanguage', 'string[]');
        // @todo Validate languages with ISO 639-1 language codes.
        // $resolver->setAllowedValues('languages', []);

        $parameters = $resolver->resolve($parameters);

        $queryKeys = array_flip(['apiKey', 'text']);
        $queryParameters = array_intersect_key($parameters, $queryKeys);
        $bodyParameters = array_diff_key($parameters, $queryKeys);
        $response = $this->send('POST', 'rest/facet', $queryParameters, $bodyParameters, true);

        /** @var FacetValue $search */
        $search = $this->getSerializer()->deserialize((string)$response->getBody(), FacetValue::class, 'json');

        return $search;
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

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    protected function prepareUri(string $path, array $queryParameters = []): string
    {
        $base_path = $this->client->getConfiguration('search_api_endpoint');
        $uri = rtrim($base_path, '/') . '/' . ltrim($path, '/');

        return $this->addQueryParameters($uri, $queryParameters);
    }
}
