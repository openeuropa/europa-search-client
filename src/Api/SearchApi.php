<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Model\Search;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class representing the Search API endpoints.
 */
class SearchApi extends ApiBase
{

    /**
     * Executes a search.
     *
     * @param array $parameters
     *   The request parameters:
     *     - apiKey: The unique key.
     *     - text: The text to match in documents. Required.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Search
     *   The search model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function search(array $parameters = []): Search
    {
        $resolver = $this->getOptionResolver();

        $resolver->setRequired('text')
            ->setAllowedTypes('text', 'string')
            ->setDefault('text', '***');

        $resolver->setDefined('query')
            ->setAllowedTypes('query', 'JsonSerializable');

        $parameters = $resolver->resolve($parameters);

        // Process parameters.
        if (isset($parameters['query'])) {
            $parameters['query'] = json_encode($parameters['query']);
        }

        $queryKeys = array_flip(['apiKey', 'text']);
        $queryParameters = array_intersect_key($parameters, $queryKeys);
        $bodyParameters = array_diff_key($parameters, $queryKeys);
        $response = $this->send('POST', 'rest/search', $queryParameters, $bodyParameters, true);

        /** @var Search $search */
        $search = $this->getSerializer()->deserialize((string)$response->getBody(), Search::class, 'json');

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
