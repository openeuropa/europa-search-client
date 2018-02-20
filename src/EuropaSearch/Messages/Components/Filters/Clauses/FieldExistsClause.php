<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FieldExistsClause.
 *
 * It defines a filter on the existence of a field.
 *
 * It does not support "NotIndexedMetadata" type.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components
 */
class FieldExistsClause extends AbstractClause
{
    /**
     * FieldExist constructor.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IndexableMetadataInterface $impliedMetadata
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
