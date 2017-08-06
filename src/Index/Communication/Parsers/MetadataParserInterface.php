<?php

/**
 * @file
 * EC\EuropaSearch\Index\Communication\Parsers\MetadataParserInterface.
 */

namespace EC\EuropaSearch\Index\Communication\Parsers;

use EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata;

/**
 * Interface MetadataParserInterface
 *
 * Defines the contract for all parser in charge of converting AbstractMetadata
 * objects into a format compliant with JSON encoding.
 *
 * @package EC\EuropaSearch\Index\Communication\Parsers
 */
interface MetadataParserInterface
{
    /**
     * Parses the metadata values to comply the Europa search services format.
     *
     * @param \EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata $metadata
     *   The metadata object to parse.
     *
     * @return array
     *   The converted metadata values where:
     *   - The key is the metadata name in Europa Search format.
     *   - The value is the metadata value compliant with the JSON format.
     */
    public function parse(AbstractMetadata $metadata);
}
