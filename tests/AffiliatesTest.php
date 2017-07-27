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

use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Entities\Affiliates;
use Unilead\HasOffers\Contain\PaymentMethod;
use Unilead\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliatesTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliatesTest extends HasoffersPHPUnit
{
    public function testCreateList()
    {
        $affiliates1 = $this->hoClient->get(Affiliates::class);
        $affiliates2 = $this->hoClient->get('Affiliates');
        $affiliates3 = $this->hoClient->get('Unilead\HasOffers\Entities\Affiliates');
        $affiliates4 = $this->hoClient->get('\Unilead\HasOffers\Entities\Affiliates');
        $affiliates5 = new Affiliates();

        isClass(Affiliates::class, $affiliates1);
        isClass(Affiliates::class, $affiliates2);
        isClass(Affiliates::class, $affiliates3);
        isClass(Affiliates::class, $affiliates4);
        isClass(Affiliates::class, $affiliates5);
    }

    public function testFindList()
    {
        $affiliates = $this->hoClient->get(Affiliates::class);
        $list = $affiliates->find();

        /** @var Affiliate $affiliate */
        $affiliate = $list[1004];

        isSame('Moscow', $affiliate->city);
        isSame('RU', $affiliate->country);
        isSame('432072', $affiliate->zipcode);
        isSame('Lvovsky 12', $affiliate->address1);

        $paymentMethod = $affiliate->getPaymentMethod();
        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());
    }

    public function testCanGetAffiliateUser()
    {
        $affiliates = $this->hoClient->get(Affiliates::class);
        $list = $affiliates->find();

        /** @var Affiliate $affiliate */
        $affiliate = $list[1004];

        $users = $affiliate->getAffiliateUser()->getList();

        isSame('10', $users->find('0.id'));
        isSame('anbelov83@belov.ru', $users->find('0.email'));
        isSame(AffiliateUser::STATUS_DELETED, $users->find('0.status'));
    }
}
