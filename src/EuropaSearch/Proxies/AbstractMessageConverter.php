<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\AbstractMessageConverter.
 */

namespace EC\EuropaSearch\Proxies;

use Composer\Semver\Semver;
use EC\EuropaWS\Proxies\MessageConverterInterface;
use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ProxyException;

/**
 * Class AbstractMessageConverter.
 *
 * Extending this class allows objects to share the message converter methods
 * that are common to all message convertions.
 *
 * @package EC\EuropaSearch\Proxies
 */
abstract class AbstractMessageConverter implements MessageConverterInterface
{

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, WSConfigurationInterface $configuration)
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
