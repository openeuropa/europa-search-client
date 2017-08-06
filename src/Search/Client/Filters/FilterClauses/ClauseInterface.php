<?php
/**
 * Created by PhpStorm.
 * User: gillesdeudon
 * Date: 6/08/17
 * Time: 10:05
 */

namespace EC\EuropaSearch\Search\Client\Filters\FilterClauses;

/**
 * Interface ClauseInterface
 *
 * It represents a simple filter clause that can be combined with other in
 * order to build the metadata filter of a search query
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
interface ClauseInterface
{

    /**
     * Gets the metadata implied in the criteria definition.
     *
     * @return \EC\EuropaSearch\Common\DocumentMetadata\MetadataInterface $impliedMetadata
     *   The name of the implied metadata.
     */
    public function getImpliedMetadata();

    /**
     * Gets the name of metadata implied in the filter.
     *
     * @return string
     *   The name of metadata
     *
     * @inheritdoc Used by the library process, should not be called outside.
     */
    public function getMetadataName();

    /**
     * Gets the type of metadata implied in the filter.
     *
     * @return string
     *   The type of metadata
     *
     * @inheritdoc Used by the library process, should not be called outside.
     */
    public function getMetadataType();
}
