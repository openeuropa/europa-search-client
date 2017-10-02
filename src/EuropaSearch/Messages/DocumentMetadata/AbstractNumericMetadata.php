<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\DocumentMetadata\AbstractNumericMetadata.
 */

namespace EC\EuropaSearch\Messages\DocumentMetadata;

/**
 * Class AbstractNumericMetadata.
 *
 * Abstract class for all numeric metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Messages\DocumentMetadata
 */
abstract class AbstractNumericMetadata extends AbstractMetadata implements IndexableMetadataInterface
{

    const EUROPA_SEARCH_NAME_PREFIX = 'esNU';

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'numeric';
    }
}
