<?php
/**
 * @file
 * Contains EC\EuropaSearch\Messages\Index\WebContentRequest.
 */

namespace EC\EuropaSearch\Messages\Index;

/**
 * Class WebContentRequest.
 *
 *  It covers the web content indexing request.
 *
 * @package EC\EuropaSearch\Messages\Index
 */
class WebContentRequest extends AbstractIndexingRequest
{

    /**
     * The content of the web content to send for indexing.
     *
     * @var string
     */
    private $documentContent;

    /**
     * Gets the content of the indexed document.
     *
     * @return string
     *    The content of the indexed document.
     */
    public function getDocumentContent()
    {
        return $this->documentContent;
    }

    /**
     * Sets the content of the indexed document.
     *
     * @param string $documentContent
     *    The document content to index.
     */
    public function setDocumentContent($documentContent)
    {
        $this->documentContent = $documentContent;
    }
}