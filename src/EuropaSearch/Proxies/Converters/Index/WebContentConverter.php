<?php

namespace EC\EuropaSearch\Proxies\Converters\Index;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Proxies\Converters\AbstractMessageConverter;
use EC\EuropaSearch\Transporters\Requests\Index\WebContentRequest;
use EC\EuropaSearch\Exceptions\ProxyException;
use EC\EuropaSearch\Messages\Index\IndexingResponse;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Class WebContentConverter.
 *
 * Converter for IndexingWebContent object.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Index
 */
class WebContentConverter extends AbstractMessageConverter
{

    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration)
    {
        $request = new WebContentRequest();

        $request->setDocumentId($message->getDocumentId());
        $request->setDocumentLanguage($message->getDocumentLanguage());
        $request->setDocumentURI($message->getDocumentURI());

        // Data retrieved from the web services configuration.
        $WSSettings = $configuration->getConnectionConfigurations();
        $request->setAPIKey($WSSettings['api_key']);
        $request->setDatabase($WSSettings['database']);

        // Clean the document content of its HTML.
        $cleanedContent = $this->formatDocumentContent($message->getDocumentContent());
        $request->setDocumentContent($cleanedContent);

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration)
    {
        $rawResult = parent::convertMessageResponse($response, $configuration);

        if (empty($rawResult->reference)) {
            throw new ProxyException("The reference is not returned by the service; which indicating a problem");
        }

        return new IndexingResponse($rawResult->reference, $rawResult->trackingId);
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
