<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Index\IndexedWebContent.
 */

namespace EC\EuropaSearch\Messages\Index;

use EC\EuropaWS\Proxies\ProxyProvider;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IndexedWebContent.
 *
 * It defines a web content document that is sent for indexing to the Europa Search
 * services.
 *
 * @package EC\EuropaSearch\Messages\Index
 */
class IndexedWebContent extends AbstractIndexedDocument
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

    /**
     * {@inheritdoc}
     */
    public function getProxyIdentifier()
    {
        return ProxyProvider::MESSAGE_ID_PREFIX.'index.webContent';
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('documentContent', new Assert\NotBlank());
    }
}
