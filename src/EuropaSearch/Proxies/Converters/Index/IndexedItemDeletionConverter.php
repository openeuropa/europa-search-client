<?php

namespace EC\EuropaSearch\Proxies\Converters\Index;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;
use EC\EuropaSearch\Proxies\Converters\AbstractMessageConverter;
use EC\EuropaSearch\Transporters\Requests\Index\IndexedItemDeletionRequest;

/**
 * Class IndexedItemDeletionConverter.
 *
 * Converter for IndexedItemDeletionRequest object.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Index
 */
class IndexedItemDeletionConverter extends AbstractMessageConverter
{

    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration)
    {
        $request = new IndexedItemDeletionRequest();

        $request->setDocumentId($message->getDocumentId());

        // Data retrieved from the web services configuration.
        $WSSettings = $configuration->getConnectionConfigurations();
        $request->setAPIKey($WSSettings['api_key']);
        $request->setDatabase($WSSettings['database']);

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, EuropaSearchConfig $configuration)
    {
        return $this->convertMessage($message);
    }
}
