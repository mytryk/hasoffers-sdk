<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace JBZoo\PHPUnit;

use Item8\HasOffers\Entity\Advertiser;
use Item8\HasOffers\Entity\AdvertiserUser;

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
        $advertiser3 = $this->hoClient->get('Item8\\HasOffers\\Entity\\Advertiser');
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
     * @expectedException           \Item8\HasOffers\Exception
     * @expectedExceptionMessage    Property "id" read only in Item8\HasOffers\Entity\Advertiser
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
     * @expectedExceptionMessage    No data to create new object "Item8\HasOffers\Entity\Advertiser" in HasOffers
     * @expectedException           \Item8\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $advertiser = $this->hoClient->get(Advertiser::class);
        $advertiser->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Item8\HasOffers\Entity\Advertiser
     * @expectedException \Item8\HasOffers\Exception
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
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $this->testId);
        $answers = $advertiser->getAnswers();

        isSame(1, count($answers));
        isSame('What language do you speak?', $answers[0]['question']);
        isSame('English', $answers[0]['answer']);
    }

    public function testGetAdvertiserUser()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, $this->testId);
        $users = $advertiser->getAdvertiserUser()->getList();

        isSame('2', $users[0]['id']);
        isNotEmpty($users[0]['email']);
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
