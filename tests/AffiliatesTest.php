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

use Unilead\HasOffers\Contain\PaymentMethod;
use Unilead\HasOffers\Entities\Affiliates;
use Unilead\HasOffers\Entity\Affiliate;

/**
 * Class AffiliatesTest
 * @package JBZoo\PHPUnit
 */
class AffiliatesTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        /** @var Affiliates $affiliates */
        $affiliates = $this->hoClient->get(Affiliates::class);
        $list = $affiliates->find();

        /** @var Affiliate $affiliate */
        $affiliate = $list[1004];

        isSame('Moscow', $affiliate->city);
        $paymentMethod = $affiliate->getPaymentMethod();
        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());
    }
}
