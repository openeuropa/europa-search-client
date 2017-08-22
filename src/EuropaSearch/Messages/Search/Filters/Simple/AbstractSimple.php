<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Simple\AbstractSimple.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Simple;

use EC\EuropaSearch\Messages\DocumentMetadata\IndexableMetadataInterface;
use EC\EuropaSearch\Messages\Search\Filters\BoostableFilter;
use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Proxies\BasicProxyController;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractSimple.
 *
 * Default definition of a simple filter.
 *
 * The actual filter implementation are in its extension.
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Simple
 */
abstract class AbstractSimple extends BoostableFilter implements ComponentInterface
{

    /**
     * Prefix applicable to all converter id of classes extending this class.
     *
     * @const
     */
    const CONVERTER_NAME_PREFIX = BasicProxyController::COMPONENT_ID_PREFIX.'searching.filters.simple.';

    /**
     * The metadata implied in the criteria definition.
     *
     * @var \EC\EuropaSearch\Messages\DocumentMetadata\AbstractMetadata
     */
    protected $impliedMetadata;

    /**
     * Gets the metadata implied in the criteria definition.
     *
     * @return \EC\EuropaSearch\Messages\DocumentMetadata\AbstractMetadata $impliedMetadata
     *   The name of the implied metadata.
     */
    public function getImpliedMetadata()
    {
        return $this->impliedMetadata;
    }

    /**
     * Sets the name of the metadata implied in the criteria definition.
     *
     * @param \EC\EuropaSearch\Messages\DocumentMetadata\IndexableMetadataInterface $impliedMetadata
     *   The implied metadata.
     */
    public function setImpliedMetadata(IndexableMetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('impliedMetadata', [
            new Assert\NotBlank(),
            new Assert\Valid(['traverse' => true]),
        ]);
    }
}
