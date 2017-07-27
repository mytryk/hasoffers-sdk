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
 *
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
        $someId = '12';
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, $someId);

        is($someId, $advertiserUser->id);
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\AdvertiserUser" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
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
        $someId = '12';
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, $someId);
        is($someId, $advertiserUser->id);

        $advertiserUser->undefined_property;
    }

    public function testCanCreateAdvertiserUser()
    {
        $this->skipIfFakeServer();

        $password = Str::random(13);
        $email = Str::random(10) . '@' . Str::random(5) . '.com';
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class);
        $advertiserUser->advertiser_id = '524';
        $advertiserUser->first_name = 'Test Company';
        $advertiserUser->phone = '+7 845 845 84 54';
        $advertiserUser->email = $email;
        $advertiserUser->password = $password;
        $advertiserUser->password_confirmation = $password;
        $advertiserUser->save();

        /** @var AdvertiserUser $advertiserCheck */
        $advertiserCheck = $this->hoClient->get(AdvertiserUser::class, $advertiserUser->id);

        isSame($advertiserUser->id, $advertiserCheck->id);
        isSame($advertiserUser->first_name, $advertiserCheck->first_name);
        isSame($advertiserUser->phone, $advertiserCheck->phone);
        isSame($advertiserUser->email, $advertiserCheck->email);
    }

    public function testCanUpdateAdvertiserUser()
    {
        $this->skipIfFakeServer();

        /** @var AdvertiserUser $advertiserUserBeforeSave */
        $advertiserUserBeforeSave = $this->hoClient->get(AdvertiserUser::class, 12);

        $beforeFirstName = $advertiserUserBeforeSave->first_name;
        $advertiserUserBeforeSave->first_name = Str::random();
        $advertiserUserBeforeSave->save();

        /** @var AdvertiserUser $advertiserAfterSave */
        $advertiserAfterSave = $this->hoClient->get(AdvertiserUser::class, 12);
        isNotSame($beforeFirstName, $advertiserAfterSave->first_name);
    }

    public function testCanDeleteAdvertiserUser()
    {
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, 12);

        $advertiserUser->delete();

        isSame(AdvertiserUser::STATUS_DELETED, $advertiserUser->status);
    }
}
