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

use JBZoo\Utils\Env;
use Unilead\HasOffers\Entity\Advertiser;
use Unilead\HasOffers\HasOffersClient;

/**
 * Class AdvertiserTest
 * @package JBZoo\PHPUnit
 */
class AdvertiserTest extends PHPUnit
{
    /**
     * @var HasOffersClient
     */
    protected $hoClient;

    public function setUp()
    {
        parent::setUp();

        $this->hoClient = new HasOffersClient(
            Env::get('HO_API_NETWORK_ID'),
            Env::get('HO_API_NETWORK_TOKEN')
        );
    }

    public function testCreatingAdvertiserWays()
    {
        $advertiser1 = $this->hoClient->get(Advertiser::class); // recommended!
        $advertiser2 = $this->hoClient->get('Advertiser');
        $advertiser3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\Advertiser');
        $advertiser4 = new Advertiser();
        $advertiser4->setClient($this->hoClient);

        isClass(Advertiser::class, $advertiser1);
        isClass(Advertiser::class, $advertiser2);
        isClass(Advertiser::class, $advertiser3);
        isClass(Advertiser::class, $advertiser4);

        isNotSame($advertiser1, $advertiser2);
        isNotSame($advertiser1, $advertiser3);
    }

    public function testCanGetAdvertiserById()
    {
        $someId = '504';
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $someId);

        is($someId, $advertiser->id);
    }

    /**
     * @expectedExceptionMessage Missing required argument: data
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $advertiser = $this->hoClient->get(Advertiser::class);
        $advertiser->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\Advertiser
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $someId = '504';
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $someId);
        is($someId, $advertiser->id);

        $advertiser->undefined_property;
    }

    public function testCanCreateAdvertiser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class);
        $advertiser->company = 'Test Company';
        $advertiser->phone = '+7 845 845 84 54';
        $advertiser->zipcode = '432543';
        $advertiser->save();

        /** @var Advertiser $advertiserCheck */
        $advertiserCheck = $this->hoClient->get(Advertiser::class, $advertiser->id);

        isSame($advertiser->id, $advertiserCheck->id);
        isSame($advertiser->company, $advertiserCheck->company);
        isSame($advertiser->phone, $advertiserCheck->phone);
        isSame($advertiser->zipcode, $advertiserCheck->zipcode);
    }

    public function testCanUpdateAdvertiser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class);

        $advertiser->id = 5004;
        $advertiser->company = 'Test Company';
        $advertiser->phone = '+7 845 845 84 54';
        $advertiser->status = Advertiser::STATUS_ACTIVE;
        $advertiser->zipcode = '432543';
        $advertiser->save();

        /** @var Advertiser $advertiserCheck */
        $advertiserCheck = $this->hoClient->get(Advertiser::class, $advertiser->id);

        isSame($advertiser->id, $advertiserCheck->id);
        isSame($advertiser->company, $advertiserCheck->company);
        isSame($advertiser->phone, $advertiserCheck->phone);
        isSame($advertiser->zipcode, $advertiserCheck->zipcode);
    }

    public function testCanDeleteAdvertiser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, 504);

        $advertiser->delete();

        isSame(Advertiser::STATUS_DELETED, $advertiser->status);
    }

    public function testCanRestoreAdvertiser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, 504);
        $advertiser->restore();

        isSame(Advertiser::STATUS_ACTIVE, $advertiser->status);
    }
}
