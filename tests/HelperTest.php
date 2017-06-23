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

namespace JBZoo\PHPUnit;

use JBZoo\Data\JSON;
use Unilead\HasOffers\Helper;

/**
 * Class HelperTest
 * @package JBZoo\PHPUnit
 */
class HelperTest extends HasoffersPHPUnit
{
    /**
     * @var array
     */
    static protected $originalArray = [
        'null'         => null,
        'NetworkToken' => 'will_remove',
        '_url'         => 'phalcon_url_for_routing',
        'some'         => 'parameter',
        'array'        => [],
        'sub.array'    => [
            'some'      => 'parameter',
            'array'     => [],
            'sub.array' => [
                'some' => 'parameter',
            ],
            'string'    => '',
            'number'    => 0,
            'bool'      => false,
        ],
        'string'       => '',
        'number'       => 0,
        'false'        => false,
        'true'         => true,
    ];

    public function testArrayNormolize()
    {
        $expectedArray = [
            'false'     => '0',
            'number'    => '0',
            'some'      => 'parameter',
            'string'    => '',
            'sub_array' => [
                'bool'      => '0',
                'number'    => '0',
                'some'      => 'parameter',
                'string'    => '',
                'sub.array' => [
                    'some' => 'parameter',
                ],
            ],
            'true'      => '1'
        ];

        $normolized = Helper::normolizeArray(self::$originalArray);
        isSame($expectedArray, $normolized);
    }

    public function testHashFromArray()
    {
        $hash = Helper::hash(self::$originalArray);
        isSame('ho_sdk_b82bbf7bb34c6a3424b037fa0036b7ab0d503ba4', $hash);
    }

    public function testJsonNormolize()
    {
        isSame(
            '' . json(Helper::normolizeArray(self::$originalArray)),
            Helper::normolizeJson(self::$originalArray)
        );

        isSame(
            '' . json(Helper::normolizeArray(self::$originalArray)),
            Helper::normolizeJson('' . json(self::$originalArray))
        );
    }
}
