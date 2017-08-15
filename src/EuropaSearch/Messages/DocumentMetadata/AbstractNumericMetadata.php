<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\DocumentMetadata\AbstractNumericMetadata.
 */

namespace EC\EuropaSearch\Messages\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractNumericMetadata.
 *
 * Abstract class for all numeric metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Messages\DocumentMetadata
 */
abstract class AbstractNumericMetadata extends AbstractMetadata
{

    /**
     * {@inheritdoc}
     */
    public function getEuropaSearchName()
    {
        return 'esNU_'.$this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'numeric';
    }
}
