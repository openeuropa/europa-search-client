<?php

namespace EC\EuropaSearch\Proxies\Converters\Components\Utils;

/**
 * Class DateComponentConverter.
 *
 * Utils class for converting date component values.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components\Utils
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
