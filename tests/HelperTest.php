<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace JBZoo\PHPUnit;

use function JBZoo\Data\json;
use Item8\HasOffers\Helper;

/**
 * Class HelperTest
 *
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
        'data'         => [
            'phone'   => '+7 845 845 84 54',
            'company' => 'Test Company',
        ],
        'string'       => '',
        'number'       => 0,
        'false'        => false,
        'true'         => true,
    ];

    public function testArrayNormolize()
    {
        $expectedArray = [
            'data'      => [
                'company' => 'Test Company',
                'phone'   => '+7 845 845 84 54',
            ],
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
            'true'      => '1',
        ];

        $normolized = Helper::normolizeArray(self::$originalArray);
        isSame($expectedArray, $normolized);
    }

    public function testHashFromArray()
    {
        $hash = Helper::hash(self::$originalArray);
        isSame('ho_sdk_95c09190e9d7b742ff5a57a49c24af58c610820f', $hash);
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
