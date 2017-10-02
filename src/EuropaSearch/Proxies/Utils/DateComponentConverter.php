<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Utils\DateComponentConverter.
 */

namespace EC\EuropaSearch\Proxies\Utils;

/**
 * Class DateComponentConverter.
 *
 * Utils class for converting date component values.
 *
 * @package EC\EuropaSearch\Proxies\Utils
 */
class DateComponentConverter
{

    /**
     *  Gets the date value consumable by Europa Search service.
     *
     * @param mixed $value
     *   The raw date value to convert.
     * @return mixed $finalValue
     *   The converted date values.
     */
    protected function getConvertedDateValue($value)
    {

        $dateTime = new \DateTime($value);

        return $dateTime->format('Y-m-d\TH:i:sP');
    }
}
