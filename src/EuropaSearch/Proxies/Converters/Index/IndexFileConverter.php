<?php

namespace EC\EuropaSearch\Proxies\Converters\Index;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Proxies\Converters\AbstractMessageConverter;
use EC\EuropaSearch\Transporters\Requests\Index\IndexFileRequest;
use EC\EuropaSearch\Exceptions\ProxyException;
use EC\EuropaSearch\Messages\Index\IndexingResponse;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Class IndexFileConverter.
 *
 * Converter for IndexFile object.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Index
 */
class IndexFileConverter extends AbstractMessageConverter
{
    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration)
    {
        $request = new IndexFileRequest();

        $request->setDocumentId($message->getDocumentId());
        $request->setDocumentLanguage($message->getDocumentLanguage());
        $request->setDocumentURI($message->getDocumentURI());

        // Data retrieved from the web services configuration.
        $WSSettings = $configuration->getConnectionConfigurations();
        $request->setAPIKey($WSSettings['api_key']);
        $request->setDatabase($WSSettings['database']);

        $filePath = $message->getDocumentFile();
        $request->setDocumentFile($filePath);

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
}
