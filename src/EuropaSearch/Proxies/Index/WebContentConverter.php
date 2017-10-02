<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Index\WebContentConverter.
 */

namespace EC\EuropaSearch\Proxies\Index;

use EC\EuropaSearch\Proxies\AbstractMessageConverter;
use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaSearch\Messages\Index\WebContentRequest;
use EC\EuropaWS\Exceptions\ProxyException;
use EC\EuropaWS\Messages\StringResponseMessage;
use EC\EuropaWS\Messages\ValidatableMessageInterface;

/**
 * Class WebContentConverter.
 *
 * Converter for IndexingWebContent object.
 *
 * @package EC\EuropaSearch\Proxies\Index
 */
class WebContentConverter extends AbstractMessageConverter
{

    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, WSConfigurationInterface $configuration)
    {
        throw new ProxyException('The "convertMessage()" method is not supported.');
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, WSConfigurationInterface $configuration)
    {
        $request = new WebContentRequest();

        $request->setDocumentId($message->getDocumentId());
        $request->setDocumentLanguage($message->getDocumentLanguage());
        $request->setDocumentURI($message->getDocumentURI());
        $request->addConvertedComponents($convertedComponent);

        // Data retrieved from the web services configuration.
        $WSSettings = $configuration->getConnectionConfig();
        $request->setAPIKey($WSSettings['APIKey']);
        $request->setDatabase($WSSettings['database']);

        // Clean the document content of its HTML.
        $cleanedContent = $this->formatDocumentContent($message->getDocumentContent());
        $request->setDocumentContent($cleanedContent);

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, WSConfigurationInterface $configuration)
    {
        $rawResult = parent::convertMessageResponse($response, $configuration);

        if (empty($rawResult->reference)) {
            throw new ProxyException("The reference is not returned by the service; which indicating a problem");
        }

        return new StringResponseMessage($rawResult->reference);
    }

    /**
     * Formats the web content before sending the request.
     *
     * @param string $documentContent
     *   The content to clean.
     *
     * @return string
     *  The cleaned content.
     */
    private function formatDocumentContent($documentContent)
    {
        return strip_tags($documentContent);
    }
}
