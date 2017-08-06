<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\FieldExistsClause.
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Clause to filter on the existence of a field.
 *
 * @inheritdoc
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
class FieldExistsClause extends AbstractClause
{

    /**
     * FieldExistsClause constructor.
     *
     * @param MetadataInterface $impliedMetadata
     *   The metadata implied in the filter.
     */
    public function __construct(MetadataInterface $impliedMetadata)
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
        $metadata->addPropertyConstraint('impliedMetadata', new Assert\Expression(array(
            'expression' => 'this.getMetadataType() != "'.NotIndexedMetadata::TYPE.'"',
            'message' => 'The metadata is not supported for this kind of clause.',
        )));
    }
}
