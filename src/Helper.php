<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     Proprietary
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers;

use JBZoo\Utils\Url;
use function JBZoo\Data\json;

/**
 * Class Helper
 * @package Item8\HasOffers
 */
class Helper
{
    /**
     * @param array $array
     * @param int   $sortFlags
     * @return bool
     */
    public static function ksortRecursive(&$array, $sortFlags = SORT_REGULAR)
    {
        if (!is_array($array)) {
            return false;
        }

        ksort($array, $sortFlags);

        foreach ($array as &$arr) {
            self::ksortRecursive($arr, $sortFlags);
        }

        return true;
    }

    /**
     * @param $array
     * @return mixed
     */
    public static function normolizeArray($array)
    {
        $excludedProps = [
            'NetworkToken',
            '_url',
        ];

        parse_str(Url::build($array), $array);
        self::ksortRecursive($array);

        foreach ($excludedProps as $prop) {
            if (array_key_exists($prop, $array)) {
                unset($array[$prop]);
            }
        }

        return $array;
    }

    /**
     * @param $jsonOrArray
     * @return string
     */
    public static function normolizeJson($jsonOrArray)
    {
        $array = json($jsonOrArray)->getArrayCopy();
        $array = self::normolizeArray($array);
        $json = json($array);
        return $json->__toString();
    }

    /**
     * @param array  $array
     * @param string $prefix
     * @return string
     */
    public static function hash($array, $prefix = 'ho_sdk_')
    {
        $array = self::normolizeArray($array);
        return $prefix . sha1(serialize($array));
    }
}
