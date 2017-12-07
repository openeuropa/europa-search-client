<?php

namespace EC\EuropaSearch\Proxies\Converters;

use Composer\Semver\Semver;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;
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
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, EuropaSearchConfig $configuration)
    {
        $request = $this->convertMessage($message, $configuration);

        if (!empty($convertedComponent)) {
            $request->addConvertedComponents($convertedComponent);
        }

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration)
    {
        try {
            $rawResult = \GuzzleHttp\json_decode($response->getBody()->getContents());

            return $rawResult;
        } catch (\InvalidArgumentException $iae) {
            throw new ProxyException("The service response is not recognized by the client", $iae);
        }
    }
}
