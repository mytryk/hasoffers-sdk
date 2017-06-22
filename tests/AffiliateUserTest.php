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
use Unilead\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliateUserTest
 * @package JBZoo\PHPUnit
 */
class AffiliateUserTest extends HasoffersPHPUnit
{
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
        $someId = '504';
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, $someId);

        is($someId, $affiliateUser->id);
    }

    /**
     * @expectedExceptionMessage Missing required argument: data
     * @expectedException \Unilead\HasOffers\Exception
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
        $someId = '504';
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, $someId);
        is($someId, $affiliateUser->id);

        $affiliateUser->undefined_property;
    }

    public function testCanCreateAffiliateUser()
    {
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class);
        $affiliateUser->company = 'Test Company';
        $affiliateUser->phone = '+7 845 845 84 54';
        $affiliateUser->zipcode = '432543';
        $affiliateUser->save();

        /** @var AffiliateUser $affiliateCheck */
        $affiliateCheck = $this->hoClient->get(AffiliateUser::class, $affiliateUser->id);

        isSame($affiliateUser->id, $affiliateCheck->id);
        isSame($affiliateUser->company, $affiliateCheck->company);
        isSame($affiliateUser->phone, $affiliateCheck->phone);
        isSame($affiliateUser->zipcode, $affiliateCheck->zipcode);
    }

    public function testCanUpdateAffiliateUser()
    {
        /** @var AffiliateUser $affiliateUserBeforeSave */
        $affiliateUserBeforeSave = $this->hoClient->get(AffiliateUser::class, 504);

        $beforeCompany = $affiliateUserBeforeSave->company;
        $affiliateUserBeforeSave->company = Str::random();
        $affiliateUserBeforeSave->save();

        /** @var AffiliateUser $affiliateAfterSave */
        $affiliateAfterSave = $this->hoClient->get(AffiliateUser::class, 504);
        isNotSame($beforeCompany, $affiliateAfterSave->company);
    }

    public function testCanDeleteAffiliateUser()
    {
        /** @var AffiliateUser $affiliateUser */
        $affiliateUser = $this->hoClient->get(AffiliateUser::class, 504);

        $affiliateUser->delete();

        isSame(AffiliateUser::STATUS_DELETED, $affiliateUser->status);
    }
}
