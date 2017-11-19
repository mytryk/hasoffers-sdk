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

use JBZoo\Utils\Str;
use Unilead\HasOffers\Entity\AdvertiserUser;

/**
 * Class AdvertiserUserTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserUserTest extends HasoffersPHPUnit
{
    protected $testId = '12';

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
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, $this->testId);

        is($this->testId, $advertiserUser->id);
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
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, $this->testId);
        is($this->testId, $advertiserUser->id);

        $advertiserUser->undefined_property;
    }

    public function testCanCreateAdvertiserUser()
    {
        $this->skipIfFakeServer();

        $password = Str::random();
        $email = $this->faker->companyEmail;
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class);
        $advertiserUser->advertiser_id = '500';
        $advertiserUser->first_name = $this->faker->company;
        $advertiserUser->phone = $this->faker->phoneNumber;
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

        $advertiserUser->delete(); // Clean up after test
    }

    public function testCanUpdateAdvertiserUser()
    {
        $this->skipIfFakeServer();

        /** @var AdvertiserUser $advertiserUserBeforeSave */
        $advertiserUserBeforeSave = $this->hoClient->get(AdvertiserUser::class, $this->testId);

        $beforeFirstName = $advertiserUserBeforeSave->first_name;
        $advertiserUserBeforeSave->first_name = $this->faker->firstName();
        $advertiserUserBeforeSave->save();

        /** @var AdvertiserUser $advertiserAfterSave */
        $advertiserAfterSave = $this->hoClient->get(AdvertiserUser::class, $this->testId);
        isNotSame($beforeFirstName, $advertiserAfterSave->first_name);
    }

    public function testCanDeleteAdvertiserUser()
    {
        /** @var AdvertiserUser $advertiserUser */
        $advertiserUser = $this->hoClient->get(AdvertiserUser::class, $this->testId);

        $advertiserUser->delete();

        isSame(AdvertiserUser::STATUS_DELETED, $advertiserUser->status);
    }
}
