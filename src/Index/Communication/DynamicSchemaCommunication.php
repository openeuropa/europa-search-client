<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Communication\DynamicSchemaConverter.
 */

namespace EC\EuropaSearch\Index\Communication;

use EC\EuropaSearch\Common\DocumentMetadata;
use EC\EuropaSearch\Common\Exceptions\CommunicationException;
use EC\EuropaSearch\Common\Exceptions\ConversionException;
use EC\EuropaSearch\Common\Exceptions\TransmissionException;
use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Index\Client\IndexedDocument;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Index\Transmission\IndexingRequest;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class DynamicSchemaCommunication
 *
 * It implements CommunicationInterface.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Index\Communication
 */
class DynamicSchemaCommunication implements CommunicationInterface
{

    private $serviceConfiguration;


    private $container;

    /**
     * DynamicSchemaConverter constructor.
     * @param ServiceConfiguration $serviceConfiguration
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->serviceConfiguration = $serviceConfiguration;
        // No need to validate again the configuration as done in the Client
        // layer.
        $this->container = new IndexServiceContainer($serviceConfiguration);
    }

    /**
     * @inheritDoc
     *
     * @param IndexedDocument $indexedDocument
     *   The document to convert and communicate for transmission.
     * @return string
     *   The indexed document reference.
     */
    public function communicateContentIndexing(IndexedDocument $indexedDocument)
    {
        try {
            $objectToTransmit = $this->convertSentObject($indexedDocument);
        } catch (Exception $e) {
            $message = 'The indexing request formatting failed: ';
            $message .= $e->getMessage();
            throw new ConversionException($message);
        }

        try {
            if (!$objectToTransmit->isFile()) {
                return $this->container->get('transmitter')->transmitWebContentRequest($objectToTransmit);
            }

            return $this->container->get('transmitter')->transmitFileRequest($objectToTransmit);
        } catch (TransmissionException $te) {
            $message = 'The indexing request sending failed: ';
            $message .= $te->getMessage();
            throw new CommunicationException($message);
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $documentId
     *   The document id of indexed document to delete.
     * @return string
     *   The document id.
     */
    public function communicateIndexDelete($documentId)
    {
        try {
            $objectToTransmit = new IndexingRequest(
                $this->serviceConfiguration->getApiKey(),
                $this->serviceConfiguration->getDatabase(),
                $documentId
            );

            return $this->container->get('transmitter')->transmitIndexDeleteRequest($objectToTransmit);
        } catch (TransmissionException $te) {
            $message = 'The deleting request sending failed: ';
            $message .= $te->getMessage();
            throw new CommunicationException($message);
        }
    }

    /**
     * Converts the indexDocument into IndexingRequest.
     *
     * @param IndexedDocument $indexedDocument
     *   The document to convert.
     *
     * @return IndexingRequest
     *   The object ready to be transmitted.
     */
    public function convertSentObject(IndexedDocument $indexedDocument)
    {
        $objectToTransmit = new IndexingRequest(
            $this->serviceConfiguration->getApiKey(),
            $this->serviceConfiguration->getDatabase(),
            $indexedDocument->getDocumentId()
        );

        $objectToTransmit->setLanguage($indexedDocument->getDocumentLanguage());
        $objectToTransmit->setUri($indexedDocument->getDocumentURI());

        $objectToTransmit->setIsFile(true);
        if ($indexedDocument->getDocumentType() == IndexedDocument::WEB_CONTENT) {
            $content = $indexedDocument->getDocumentContent();
            $objectToTransmit->setContent(strip_tags($content));
            $objectToTransmit->setIsFile(false);
        }

        $metadataJSON = $this->convertMetadataToJSON($indexedDocument->getMetadata());
        $objectToTransmit->setMetadataJSON($metadataJSON);

        return $objectToTransmit;
    }



    /**
     * Converts DocumentMetadata into JSON consumable by Europa Search service.
     *
     * @param array $metadataList
     *   The DocumentMetadata array to convert.
     * @return string
     *   The list of metadata encoded in JSON format.
     *
     */
    protected function convertMetadataToJSON($metadataList = array())
    {

        if (empty($metadataList)) {
            return false;
        }

        $convertList = array();
        foreach ($metadataList as $name => $metadata) {
            $convertMetaData = $this->encodeMetadata($metadata);
            if ($convertMetaData) {
                $convertList = array_merge($convertList, $convertMetaData);
            }
        }

        return json_encode($convertList);
    }



    /**
     * Converts DocumentMetadata into JSON key-value.
     *
     * The key will be compliant with the Europa search syntax.
     *
     * @param DocumentMetadata $metadata
     *   The DocumentMetadata to convert.
     * @return array
     *   The converted metadata where:
     *   - The key is the metadata name formatted for the Europa Search services;
     *   - The value in the right format for for the Europa Search services.
     * @SuppressWarnings(PHPMD)
     */
    protected function encodeMetadata(DocumentMetadata $metadata)
    {
        $value = $metadata->getValue();
        $name = $metadata->getName();

        switch ($metadata->getType()) {
            case 'fulltext':
                $name = 'esIN_'.$name;
                break;

            case 'uri':
            case 'string':
                $name = 'esST_'.$name;
                break;

            case 'long':
            case 'int':
            case 'integer':
            case 'double':
            case 'float':
            case 'decimal':
                $name = 'esNU_'.$name;
                break;

            case 'boolean':
                $name = 'esBO_'.$name;
                $value = $this->encodeMetadataBooleanValue($value);
                break;

            case 'date':
                $name = 'esDA_'.$name;
                $value = $this->encodeMetadataDateValue($value);
                break;

            default:
                $name = 'esNI_'.$name;
                break;
        }

        return array($name => $value);
    }

    /**
     * Gets the date metadata value consumable by Europa Search service.
     *
     * @param array|string $value
     *   The raw date value to convert.
     * @return array|string
     *   The converted date value.
     */
    private function encodeMetadataDateValue($value)
    {
        if (is_array($value)) {
            $finalValue = array();
            foreach ($value as $item) {
                $dateTime = new \DateTime($item);
                $finalValue[] = $dateTime->format(\DateTime::ISO8601);
            }

            return $finalValue;
        }

        $dateTime = new \DateTime($value);

        return $dateTime->format(\DateTime::ISO8601);
    }

    /**
     * Gets the boolean metadata value consumable by Europa Search service.
     *
     * @param array|boolean $value
     *   The raw date value to convert.
     * @return array|boolean
     *   The converted date value.
     */
    private function encodeMetadataBooleanValue($value)
    {
        if (is_array($value)) {
            $finalValue = array();
            foreach ($value as $item) {
                $finalValue[] = boolval($item);
            }

            return $finalValue;
        }

        return boolval($value);
    }
}
