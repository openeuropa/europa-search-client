<?php

namespace EC\EuropaSearch\Proxies\Converters;

use Composer\Semver\Semver;
use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Exceptions\ProxyException;

/**
 * Class AbstractMessageConverter.
 *
 * Extending this class allows objects to share the message converter methods
 * that are common to all message conversions.
 *
 * @package EC\EuropaSearch\Proxies\Converters
 */
abstract class AbstractMessageConverter implements MessageConverterInterface
{

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration)
    {
        try {
            $rawResult = \GuzzleHttp\json_decode($response->getBody()->getContents());

            if (!isset($rawResult->apiVersion)) {
                throw new ProxyException("The api version has not been communicated by the web service");
            }

            $supportedAPI = $configuration->getSupportedServiceAPIVersion();
            if (!Semver::satisfies($rawResult->apiVersion, $supportedAPI)) {
                throw new ProxyException("The service api version is incompatible with the client's supported one: ".$supportedAPI);
            }

            return $rawResult;
        } catch (\InvalidArgumentException $iae) {
            throw new ProxyException("The service response is not recognized by the client", $iae);
        }
    }
}
