<?php

namespace EC\EuropaSearch\Messages\Components\Filters\Clauses;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface;
use EC\EuropaSearch\Messages\Components\Filters\BoostableFilter;
use EC\EuropaSearch\Messages\Components\ComponentInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractSimple.
 *
 * Default definition of a simple filter.
 *
 * The actual filter implementation are in its extension.
 *
 * @package EC\EuropaSearch\Messages\Components\Filters\Clauses
 *
 * {@internal It only exists for only declaring methods common to sub-classes.}
 */
abstract class AbstractClause extends BoostableFilter implements ComponentInterface
{

    /**
     * Prefix applicable to all converter id of classes extending this class.
     *
     * @const
     */
    const CONVERTER_NAME_PREFIX = 'europaSearch.componentProxy.searching.filters.clauses.';

    /**
     * The metadata implied in the criteria definition.
     *
     * @var \EC\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata
     */
    protected $impliedMetadata;

    /**
     * Gets the metadata implied in the criteria definition.
     *
     * @return \EC\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata $impliedMetadata
     *   The name of the implied metadata.
     */
    public function getImpliedMetadata()
    {
        return $this->impliedMetadata;
    }

    /**
     * Sets the name of the metadata implied in the criteria definition.
     *
     * @param \EC\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface $impliedMetadata
     *   The implied metadata.
     */
    public function setImpliedMetadata(IndexableMetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('impliedMetadata', [
            new Assert\NotBlank(),
            new Assert\Valid(['traverse' => true]),
        ]);
    }
}
