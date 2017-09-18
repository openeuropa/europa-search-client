<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Clauses\FieldExistsClause.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Clauses;

use EC\EuropaSearch\Messages\DocumentMetadata\IndexableMetadataInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FieldExistsClause.
 *
 * It defines a filter on the existence of a field.
 *
 * It does not support "NotIndexedMetadata" type.
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Clauses
 */
class FieldExistsClause extends AbstractClause
{

    /**
     * FieldExist constructor.
     *
     * @param IndexableMetadataInterface $impliedMetadata
     *   The metadata pointed by the filters.
     */
    public function __construct(IndexableMetadataInterface $impliedMetadata)
    {
        $this->impliedMetadata = $impliedMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'fieldExists';
    }
}
