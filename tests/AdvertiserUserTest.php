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

use JBZoo\Utils\Str;
use Unilead\HasOffers\Entity\AdvertiserUser;

/**
 * Class AdvertiserUserTest
 * @package JBZoo\PHPUnit
 */
class AdvertiserUserTest extends HasoffersPHPUnit
{
    public function testCreatingAdvertiserUserWays()
    {
        $advertiserUser1 = $this->hoClient->get(AdvertiserUser::class); // recommended!
        $advertiserUser2 = $this->hoClient->get('AdvertiserUser');
        $advertiserUser3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\AdvertiserUser');
        $advertiserUser4 = new AdvertiserUser();
        $advertiserUser4->setClient($this->hoClient);

        isClass(AdvertiserUser::class, $advertiserUser1);
        isClass(AdvertiserUser::class, $advertiserUser2);
        isClass(AdvertiserUser::class, $advertiserUser3);
        isClass(AdvertiserUser::class, $advertiserUser4);

        isNotSame($advertiserUser1, $advertiserUser2);
        isNotSame($advertiserUser1, $advertiserUser3);
    }

    /**
     * @expectedException           \Unilead\HasOffers\Exception
     * @expectedExceptionMessage    Property "id" read only in Unilead\HasOffers\Entity\AdvertiserUser
     */
    public function testIdReadOnly()
    {
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class);
        $advertiserUser->id = 42;
    }

    public function testCanGetAdvertiserUserById()
    {
        $someId = '504';
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, $someId);

        is($someId, $advertiserUser->id);
    }

    /**
     * @expectedExceptionMessage Missing required argument: data
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class);
        $advertiserUser->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\AdvertiserUser
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $someId = '504';
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, $someId);
        is($someId, $advertiserUser->id);

        $advertiserUser->undefined_property;
    }

    public function testCanCreateAdvertiserUser()
    {
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class);
        $advertiserUser->company = 'Test Company';
        $advertiserUser->phone = '+7 845 845 84 54';
        $advertiserUser->zipcode = '432543';
        $advertiserUser->save();

        /** @var AdvertiserUser $advertiserCheck */
        $advertiserCheck = $this->hoClient->get(AdvertiserUser::class, $advertiserUser->id);

        isSame($advertiserUser->id, $advertiserCheck->id);
        isSame($advertiserUser->company, $advertiserCheck->company);
        isSame($advertiserUser->phone, $advertiserCheck->phone);
        isSame($advertiserUser->zipcode, $advertiserCheck->zipcode);
    }

    public function testCanUpdateAdvertiserUser()
    {
        /** @var AdvertiserUser $advertiserUserBeforeSave */
        $advertiserUserBeforeSave = $this->hoClient->get(AdvertiserUser::class, 504);

        $beforeCompany = $advertiserUserBeforeSave->company;
        $advertiserUserBeforeSave->company = Str::random();
        $advertiserUserBeforeSave->save();

        /** @var AdvertiserUser $advertiserAfterSave */
        $advertiserAfterSave = $this->hoClient->get(AdvertiserUser::class, 504);
        isNotSame($beforeCompany, $advertiserAfterSave->company);
    }

    public function testCanDeleteAdvertiserUser()
    {
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, 504);

        $advertiserUser->delete();

        isSame(AdvertiserUser::STATUS_DELETED, $advertiserUser->status);
    }
}
