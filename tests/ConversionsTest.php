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

use Unilead\HasOffers\Entities\Conversions;
use Unilead\HasOffers\Entity\Conversion;

/**
 * Class ConversionsTest
 *
 * @package JBZoo\PHPUnit
 */
class ConversionsTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        $offers = $this->hoClient->get(Conversions::class);
        $list = $offers->find([
            'sort'  => ['id' => 'desc'],
            'limit' => 1,
        ]);

        isSame('124330', $list[0][Conversion::ID]);
        isSame('1006', $list[0][Conversion::AFFILIATE_ID]);
        isSame('504', $list[0][Conversion::ADVERTISER_ID]);
        isSame('10', $list[0][Conversion::OFFER_ID]);
        isSame('0', $list[0][Conversion::GOAL_ID]);
        isSame('2017-01-24 14:18:00', $list[0][Conversion::DATETIME]);
        isSame('1.65000', $list[0][Conversion::PAYOUT]);
        isSame('2.50000', $list[0][Conversion::REVENUE]);
        isSame('approved', $list[0][Conversion::STATUS]);
        isSame('1', $list[0][Conversion::IS_ADJUSTMENT]);
        isSame('cpa_flat', $list[0][Conversion::PAYOUT_TYPE]);
        isSame('cpa_flat', $list[0][Conversion::REVENUE_TYPE]);
        isSame('USD', $list[0][Conversion::CURRENCY]);
    }
}
