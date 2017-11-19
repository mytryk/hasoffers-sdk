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

use Unilead\HasOffers\Entity\Advertiser;
use Unilead\HasOffers\Entities\Advertisers;
use Unilead\HasOffers\Entity\AdvertiserUser;

/**
 * Class AdvertisersTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertisersTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testFindList()
    {
        /** @var Advertisers $advertisers */
        $advertisers = $this->hoClient->get(Advertisers::class);
        $list = $advertisers->find();

        /** @var Advertiser $advertiser */
        $advertiser = $list[$this->testId];

        isNotEmpty($advertiser->city);
    }

    public function testCanGetAdvertiserUser()
    {
        /** @var Advertisers $advertisers */
        $advertisers = $this->hoClient->get(Advertisers::class);
        $list = $advertisers->find();

        /** @var Advertiser $advertiser */
        $advertiser = $list[$this->testId];

        $users = $advertiser->getAdvertiserUser()->getList();

        isSame('2', $users[0]['id']);
    }
}
