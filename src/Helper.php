<?php
/**
 * Unilead | HasOffers
 *
 * This file is part of the Unilead Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     Proprietary
 * @copyright   Copyright (C) Unilead Network, All rights reserved.
 * @link        https://www.unileadnetwork.com
 */

namespace Unilead\HasOffers;

use JBZoo\Utils\Url;

/**
 * Class Helper
 * @package Unilead\HasOffers
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
