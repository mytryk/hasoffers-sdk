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

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Arr;
use Item8\HasOffers\Entity\Affiliate;
use Item8\HasOffers\Entities\Affiliates;

/**
 * Class AffiliatesTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliatesTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testCreateList()
    {
        $affiliates1 = $this->hoClient->get(Affiliates::class);
        $affiliates2 = $this->hoClient->get('Affiliates');
        $affiliates3 = $this->hoClient->get('Item8\HasOffers\Entities\Affiliates');
        $affiliates4 = $this->hoClient->get('\Item8\HasOffers\Entities\Affiliates');
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
        $list = $affiliates->find([
            'filters' => [
                'id' => $this->testId,
            ],
        ]);

        /** @var Affiliate $affiliate */
        $affiliate = Arr::first($list);

        isNotEmpty($affiliate->company);

        $paymentMethod = $affiliate->getPaymentMethod();
        isNotEmpty($paymentMethod);
    }

    public function testCanGetAffiliateUser()
    {
        $affiliates = $this->hoClient->get(Affiliates::class);
        $list = $affiliates->find([
            'filters' => [
                'id' => $this->testId,
            ],
        ]);

        /** @var Affiliate $affiliate */
        $affiliate = Arr::first($list);

        $users = $affiliate->getAffiliateUser()->getList();

        isSame('2', $users->find('0.id'));
    }
}
