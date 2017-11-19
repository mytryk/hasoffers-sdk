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
use Unilead\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliateUserTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateUserTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testCreatingAffiliateUserWays()
    {
        $affiliateUser1 = $this->hoClient->get(AffiliateUser::class); // recommended!
        $affiliateUser2 = $this->hoClient->get('AffiliateUser');
        $affiliateUser3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\AffiliateUser');
        $affiliateUser4 = new AffiliateUser();
        $affiliateUser4->setClient($this->hoClient);

        isClass(AffiliateUser::class, $affiliateUser1);
        isClass(AffiliateUser::class, $affiliateUser2);
        isClass(AffiliateUser::class, $affiliateUser3);
        isClass(AffiliateUser::class, $affiliateUser4);

        isNotSame($affiliateUser1, $affiliateUser2);
        isNotSame($affiliateUser1, $affiliateUser3);
    }

    /**
     * @expectedException           \Unilead\HasOffers\Exception
     * @expectedExceptionMessage    Property "id" read only in Unilead\HasOffers\Entity\AffiliateUser
     */
    public function testIdReadOnly()
    {
        $affiliateUser = $this->hoClient->get(AffiliateUser::class);
        $affiliateUser->id = 42;
    }

    public function testCanGetAffiliateUserById()
    {
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, $this->testId);

        is($this->testId, $affiliateUser->id);
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\AffiliateUser" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $affiliateUser = $this->hoClient->get(AffiliateUser::class);
        $affiliateUser->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\AffiliateUser
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, $this->testId);
        is($this->testId, $affiliateUser->id);

        $affiliateUser->undefined_property;
    }

    public function testCanCreateAffiliateUser()
    {
        $this->skipIfFakeServer();

        $password = Str::random();
        $email = $this->faker->email;
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class);
        $affiliateUser->affiliate_id = '1004';
        $affiliateUser->first_name = $this->faker->company;
        $affiliateUser->phone = $this->faker->phoneNumber;
        $affiliateUser->email = $email;
        $affiliateUser->password = $password;
        $affiliateUser->password_confirmation = $password;
        $affiliateUser->save();

        /** @var AffiliateUser $affiliateCheck */
        $affiliateCheck = $this->hoClient->get(AffiliateUser::class, $affiliateUser->id);

        isSame($affiliateUser->id, $affiliateCheck->id);
        isSame($affiliateUser->first_name, $affiliateCheck->first_name);
        isSame($affiliateUser->phone, $affiliateCheck->phone);
        isSame($affiliateUser->email, $affiliateCheck->email);

        $affiliateUser->delete(); // Clean up after test
    }

    public function testUnset()
    {
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, $this->testId);

        isTrue($affiliateUser->first_name);
        unset($affiliateUser->first_name);
        isNull($affiliateUser->first_name);

        isSame(['first_name' => null], $affiliateUser->getChangedFields());
    }

    /**
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testUnsetUndefined()
    {
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, $this->testId);

        unset($affiliateUser->undefined);
    }

    public function testIsset()
    {
        $affiliate = $this->hoClient->get(AffiliateUser::class, $this->testId);
        isTrue(isset($affiliate->first_name));
        isFalse(isset($affiliate->undefined));
    }

    public function testCanUpdateAffiliateUser()
    {
        $this->skipIfFakeServer();

        /** @var AffiliateUser $affiliateUserBeforeSave */
        $affiliateUserBeforeSave = $this->hoClient->get(AffiliateUser::class, $this->testId);

        $beforeFirstName = $affiliateUserBeforeSave->first_name;
        $affiliateUserBeforeSave->first_name = $this->faker->name();
        $affiliateUserBeforeSave->save();

        /** @var AffiliateUser $affiliateAfterSave */
        $affiliateAfterSave = $this->hoClient->get(AffiliateUser::class, $this->testId);
        isNotSame($beforeFirstName, $affiliateAfterSave->first_name);
    }

    public function testCanDeleteAffiliateUser()
    {
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, $this->testId);

        $affiliateUser->delete();

        isSame(AffiliateUser::STATUS_DELETED, $affiliateUser->status);
    }
}
