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

use Unilead\HasOffers\Entity\Advertiser;
use Unilead\HasOffers\Entities\Advertisers;
use Unilead\HasOffers\Entity\AdvertiserUser;

/**
 * Class AdvertisersTest
 * @package JBZoo\PHPUnit
 */
class AdvertisersTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        /** @var Advertisers $advertisers */
        $advertisers = $this->hoClient->get(Advertisers::class);
        $list = $advertisers->find();

        /** @var Advertiser $advertiser */
        $advertiser = $list[504];

        isSame('Moscow', $advertiser->city);
        isSame('RU', $advertiser->country);
        isSame('432072', $advertiser->zipcode);
        isSame('Lvovsky 12', $advertiser->address1);
    }

    public function testCanGetAdvertiserUser()
    {
        /** @var Advertisers $advertisers */
        $advertisers = $this->hoClient->get(Advertisers::class);
        $list = $advertisers->find();

        /** @var Advertiser $advertiser */
        $advertiser = $list[504];

        $users = $advertiser->getAdvertiserUser()->getList();

        isSame("10", $users[10]['id']);
        isSame('ivan@test.com', $users[10]['email']);
        isSame(AdvertiserUser::STATUS_ACTIVE, $users[10]['status']);
    }
}
