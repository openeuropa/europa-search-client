<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\FieldExistsClause.

 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\URLMetadata;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FieldExistsClause.
 *
 * It represents a criteria to filter on the existence of a field.
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
class FieldExistsClause extends AbstractClause
{

    /**
     * ValueFilter constructor.
     *
     * @param string $impliedMetadataName
     *   The name of metadata implied in the filter.
     * @param string $impliedMetadataType
     *   The name of metadata implied in the filter.
     *   - 'fulltext': for string that can be included in a full text search;
     *   - 'uri': for URL or URI;
     *   - 'string': for string that can be used to filter a search;
     *   - 'int' or 'integer': for integer that can be used to filter a search;
     *   - 'float': for float that can be used to filter a search;
     *   - 'boolean': for boolean that can be used to filter a search;
     *   - 'date': for date that can be used to filter a search;
     *
     */
    public function __construct($impliedMetadataName, $impliedMetadataType)
    {
        $this->impliedMetadataName = $impliedMetadataName;
        $this->impliedMetadataType = $impliedMetadataType;
    }


    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('impliedMetadataType', new Assert\Choice([
            IntegerMetadata::TYPE,
            FloatMetadata::TYPE,
            DateMetadata::TYPE,
            StringMetadata::TYPE,
            FullTextMetadata::TYPE,
            URLMetadata::TYPE,
            BooleanMetadata::TYPE,
        ]));

        $metadata->addPropertyConstraint('impliedMetadataType', new Assert\NotEqualTo(NotIndexedMetadata::TYPE));
    }
}
