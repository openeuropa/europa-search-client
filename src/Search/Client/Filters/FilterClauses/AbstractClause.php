<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\AbstractClause.
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface;
use EC\EuropaSearch\Search\Client\Filters\AbstractFilter;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract class AbstractClause.
 *
 * @inheritdoc
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
abstract class AbstractClause extends AbstractFilter implements ClauseInterface
{
    /**
     * The metadata implied in the criteria definition.
     *
     * @var \EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface
     */
    protected $impliedMetadata;

    /**
     * Gets the metadata implied in the criteria definition.
     *
     * @return \EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface $impliedMetadata
     *   The name of the implied metadata.
     */
    public function getImpliedMetadata()
    {
        return $this->impliedMetadata;
    }

    /**
     * Sets the name of the metadata implied in the criteria definition.
     *
     * @param \EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface $impliedMetadata
     *   The implied metadata.
     */
    public function setImpliedMetadata(MetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
    }

    /**
     * @inheritDoc
     *
     * @return string
     *   The type of the implied metadata.
     */
    public function getMetadataType()
    {
        return $this->impliedMetadata->getType();
    }

    /**
     * @inheritDoc
     */
    public function getMetadataName()
    {
        $this->impliedMetadata->getType();
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
            new Assert\Valid(array('traverse' => true)),
        ]);
    }
}
