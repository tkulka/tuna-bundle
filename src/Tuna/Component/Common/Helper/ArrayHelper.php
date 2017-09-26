<?php

namespace TunaCMS\Component\Common\Helper;

class ArrayHelper
{
    /**
     * @param $array
     * @param string $prefix
     *
     * @return array
     */
    public static function flattenArray(array $array, $prefix = '')
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + self::flattenArray($value, $prefix.$key.'.');
            } else {
                $result[$prefix.$key] = self::normalizeValue($value);
            }
        }

        return $result;
    }

    protected static function normalizeValue($value)
    {
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        return $value;
    }
}
