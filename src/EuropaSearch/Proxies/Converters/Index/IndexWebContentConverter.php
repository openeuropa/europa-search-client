<?php

namespace OpenEuropa\EuropaSearch\Proxies\Converters\Index;

use OpenEuropa\EuropaSearch\EuropaSearchConfig;
use OpenEuropa\EuropaSearch\Proxies\Converters\AbstractMessageConverter;
use OpenEuropa\EuropaSearch\Transporters\Requests\Index\IndexWebContentRequest;
use OpenEuropa\EuropaSearch\Exceptions\ProxyException;
use OpenEuropa\EuropaSearch\Messages\Index\IndexingResponse;
use OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Class IndexWebContentConverter.
 *
 * Converter for IndexWebContent object.
 *
 * @package OpenEuropa\EuropaSearch\Proxies\Converters\Index
 */
class IndexWebContentConverter extends AbstractMessageConverter
{
    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration)
    {
        $request = new IndexWebContentRequest();

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
            throw new ProxyException("The reference is not returned by the service.");
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
