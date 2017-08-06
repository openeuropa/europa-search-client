<?php
/**
 * Created by PhpStorm.
 * User: gillesdeudon
 * Date: 6/08/17
 * Time: 09:44
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

/**
 * Interface MetadataInterface
 *
 * It defines all Metadata classes.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
interface MetadataInterface
{

    /**
     * Sets the metadata name.
     *
     * @param string $name
     *   The metadata name.
     */
    public function setName($name);

    /**
     * Gets the metadata name.
     *
     * @return string
     *   The metadata name.
     */
    public function getName();

    /**
     * Gets metadata types.
     *
     * @return string $type
     */
    public function getType();

    /**
     * Gets the final metadata name compliant for Europa Search services.
     *
     * @return string
     *   The final name.
     *
     * @internal Used by the library process, should not be called outside.
     */
    public function getEuropaSearchName();

    /**
     * Gets the metadata values
     *
     * @return mixed $values
     *   The metadata values.
     */
    public function getValues();

    /**
     * Sets the metadata values
     *
     * @param array $values
     *   The metadata values.
     */
    public function setValues(array $values);

    /**
     * Gets the parser key to use in the communication process.
     *
     * It gives the key to retrieve the right MetadataParser for parsing.
     *
     * @return string
     *   The parser key
     */
    public static function getParserName();
}
