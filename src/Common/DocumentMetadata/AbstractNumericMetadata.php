<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\AbstractNumericMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

/**
 * Class AbstractNumericMetadata.
 *
 * It implements methods common to all numeric type of metadata.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
abstract class AbstractNumericMetadata extends AbstractMetadata
{
    /**
     * @inheritdoc
     *
     * It gets the same prefix of "String" metadata.
     *
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esST_'.$this->name;
    }
}
