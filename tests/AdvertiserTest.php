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
use Unilead\HasOffers\Entity\AdvertiserUser;

/**
 * Class AdvertiserTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserTest extends HasoffersPHPUnit
{
    protected $testId = '2';

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

    /**
     * @expectedException           \Unilead\HasOffers\Exception
     * @expectedExceptionMessage    Property "id" read only in Unilead\HasOffers\Entity\Advertiser
     */
    public function testIdReadOnly()
    {
        $advertiser = $this->hoClient->get(Advertiser::class);
        $advertiser->id = 42;
    }

    public function testCanGetAdvertiserById()
    {
        $someId = $this->testId;
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $someId);

        is($someId, $advertiser->id);
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\Advertiser" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
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
        $someId = $this->testId;
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $someId);
        is($someId, $advertiser->id);

        $advertiser->undefined_property;
    }

    public function testGetAdvertiserSignUpAnswers()
    {
        skip('TODO: Create valid in HO');

        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $this->testId);
        $answers = $advertiser->getAnswers();

        isSame(2, count($answers));
        isSame('What language do you speak?', $answers[1]['question']);
        isSame('English', $answers[1]['answer']);
    }

    public function testGetAdvertiserUser()
    {
        skip('TODO: Create valid in HO');
        /** @var Advertiser $affiliate */
        $affiliate = $this->hoClient->get(Advertiser::class, $this->testId);
        $users = $affiliate->getAdvertiserUser()->getList();

        isSame('10', $users[0]['id']);
        isSame('ivan@test.com', $users[0]['email']);
        isSame(AdvertiserUser::STATUS_ACTIVE, $users[0]['status']);
    }

    public function testCanCreateAdvertiser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class);
        $advertiser->company = $this->faker->company;
        $advertiser->phone = $this->faker->cellphoneNumber;
        $advertiser->zipcode = $this->faker->postcode;
        $advertiser->save();

        /** @var Advertiser $advertiserCheck */
        $advertiserCheck = $this->hoClient->get(Advertiser::class, $advertiser->id);

        isSame($advertiser->id, $advertiserCheck->id);
        isSame($advertiser->company, $advertiserCheck->company);
        isSame($advertiser->phone, $advertiserCheck->phone);
        isSame($advertiser->zipcode, $advertiserCheck->zipcode);

        $advertiser->delete(); // Clean up after test
    }

    public function testCanUpdateAdvertiser()
    {
        $this->skipIfFakeServer();
        /** @var Advertiser $advertiserBeforeSave */
        $advertiserBeforeSave = $this->hoClient->get(Advertiser::class, $this->testId);

        $beforeCompany = $advertiserBeforeSave->company;
        $advertiserBeforeSave->company = $this->faker->company;
        $advertiserBeforeSave->save();

        /** @var Advertiser $advertiserAfterSave */
        $advertiserAfterSave = $this->hoClient->get(Advertiser::class, $this->testId);
        isNotSame($beforeCompany, $advertiserAfterSave->company);
    }

    public function testCanDeleteAdvertiser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $this->testId);

        $advertiser->delete();

        isSame(Advertiser::STATUS_DELETED, $advertiser->status);
    }

    public function testCanRestoreAdvertiser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $this->testId);
        $advertiser->activate();

        isSame(Advertiser::STATUS_ACTIVE, $advertiser->status);
    }
}
