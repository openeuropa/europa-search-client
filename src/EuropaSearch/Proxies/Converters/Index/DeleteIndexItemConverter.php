<?php

namespace EC\EuropaSearch\Proxies\Converters\Index;

use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Exceptions\ProxyException;
use EC\EuropaSearch\Messages\Index\IndexingResponse;
use EC\EuropaSearch\Messages\ValidatableMessageInterface;
use EC\EuropaSearch\Proxies\Converters\AbstractMessageConverter;
use EC\EuropaSearch\Transporters\Requests\Index\DeleteIndexItemRequest;

/**
 * Class DeleteIndexItemConverter.
 *
 * Converter for DeleteIndexItemRequest object.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Index
 */
class DeleteIndexItemConverter extends AbstractMessageConverter
{
    /**
     * The document id that is currently converted.
     *
     * @var string
     */
    protected $currentItemToDelete;

    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration)
    {
        $this->currentItemToDelete = $message->getDocumentId();

        $request = new DeleteIndexItemRequest();
        $request->setDocumentId($this->currentItemToDelete);

        // Data retrieved from the web services configuration.
        $WSSettings = $configuration->getConnectionConfigurations();
        $request->setAPIKey($WSSettings['api_key']);
        $request->setDatabase($WSSettings['database']);

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration)
    {
        $rawResult = $response->getBody()->getContents();
        $returnedValue = boolval($rawResult);

        if (!$returnedValue) {
            $message = 'The deletion in the Europa Search index failed for '.$this->currentItemToDelete;
            throw new ProxyException($message);
        }

        return new IndexingResponse($this->currentItemToDelete);
    }
}
