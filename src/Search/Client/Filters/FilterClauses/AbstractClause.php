<?php
/**
 * @file
 * Contains EC\EuropaSearch\Search\Client\Filters\FilterClauses\AbstractClause.
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Search\Client\Filters\AbstractFilter;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract class AbstractClause.
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
abstract class AbstractClause extends AbstractFilter
{
    /**
     * Name of the metadata implied in the criteria definition.
     *
     * @var string
     */
    protected $impliedMetadataName;

    /**
     * The metadata value type.
     *
     * @var string
     */
    protected $impliedMetadataType;

    /**
     * Gets the name of the metadata implied in the criteria definition.
     *
     * @return string $impliedMetadataName
     *   The name of the implied metadata.
     */
    public function getImpliedMetadataName()
    {
        return $this->impliedMetadataName;
    }

    /**
     * Sets the name of the metadata implied in the criteria definition.
     *
     * @param string $impliedMetadataName
     *   The name of the implied metadata.
     */
    public function setImpliedMetadataName($impliedMetadataName)
    {
        $this->impliedMetadataName = $impliedMetadataName;
    }

    /**
     * Gets the type of metadata implied in the filter.
     *
     * @return string $impliedMetadataType
     *   The metadata type. the accepted type value are:
     *   - 'fulltext': for string that can be included in a full text search;
     *   - 'uri': for URL or URI;
     *   - 'string': for string that can be used to filter a search;
     *   - 'int' or 'integer': for integer that can be used to filter a search;
     *   - 'float': for float that can be used to filter a search;
     *   - 'boolean': for boolean that can be used to filter a search;
     *   - 'date': for date that can be used to filter a search;
     *   - 'not_indexed': for metadata that need to be send to Europa Search
     *      services but not indexed.
     */
    public function getImpliedMetadataType()
    {
        return $this->impliedMetadataType;
    }

    /**
     * Sets the type of metadata implied in the filter.
     *
     * @param string $impliedMetadataType
     *   The metadata type. the accepted type value are:
     *   - 'fulltext': for string that can be included in a full text search;
     *   - 'uri': for URL or URI;
     *   - 'string': for string that can be used to filter a search;
     *   - 'int' or 'integer': for integer that can be used to filter a search;
     *   - 'float': for float that can be used to filter a search;
     *   - 'boolean': for boolean that can be used to filter a search;
     *   - 'date': for date that can be used to filter a search;
     *   - 'not_indexed': for metadata that need to be send to Europa Search
     *      services but not indexed.
     */
    public function setImpliedMetadataType($impliedMetadataType)
    {
        $this->impliedMetadataType = $impliedMetadataType;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('impliedMetadataName', [
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ]);
        $metadata->addPropertyConstraints('impliedMetadataType', [
            new Assert\NotBlank(),
            new Assert\NotEqualTo(NotIndexedMetadata::TYPE),
        ]);
    }
}
