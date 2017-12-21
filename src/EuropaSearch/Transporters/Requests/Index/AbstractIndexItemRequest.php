<?php

namespace EC\EuropaSearch\Transporters\Requests\Index;

/**
 * Class AbstractIndexItemRequest.
 *
 * It defines attributes, methods specific to the item indexing.
 *
 * @package EC\EuropaSearch\Transporters\Requests\Index
 */
abstract class AbstractIndexItemRequest extends AbstractIndexingRequest
{
    /**
     * Gets the document language to send.
     *
     * @return string
     *   The document language to send.
     */
    public function getDocumentLanguage()
    {
        return $this->query['language'];
    }

    /**
     * Sets the document language to send.
     *
     * @param string $documentLanguage
     *   The document language to send.
     */
    public function setDocumentLanguage($documentLanguage)
    {
        $this->query['language'] = $documentLanguage;
    }

    /**
     * Gets the document URI to send.
     *
     * @return string
     *   The document URI to send.
     */
    public function getDocumentURI()
    {
        return $this->query['uri'];
    }

    /**
     * Sets the document URI to send.
     *
     * @param string $documentURI
     *   The document URI to send.
     */
    public function setDocumentURI($documentURI)
    {
        $this->query['uri'] = $documentURI;
    }

    /**
     * Gets the document metadata in JSON format to send.
     *
     * @return string
     *   The document metadata in JSON format to send.
     */
    public function getMetadataJSON()
    {
        return $this->body['metadata']['contents'];
    }

    /**
     * Sets the document metadata in JSON format to send.
     *
     * @param string $metadataJSON
     *   The document metadata in JSON format to send.
     */
    public function setMetadataJSON($metadataJSON)
    {
        $this->body['metadata'] = [
            'name' => 'metadata',
            'contents' => $metadataJSON,
            'headers' => ['content-type' => 'application/json'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function addConvertedComponents(array $components)
    {
        $componentsAsObject = new \stdClass();

        array_walk($components, function ($metadataDefinition, $key, $finalObject) {
            foreach ($metadataDefinition as $name => $value) {
                $finalObject->{$name} = $value;
            }
        }, $componentsAsObject);

        $json = json_encode($componentsAsObject);
        $this->setMetadataJSON($json);
    }
}
