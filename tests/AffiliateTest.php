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

use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliateTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testCreatingAffiliateWays()
    {
        $affiliate1 = $this->hoClient->get(Affiliate::class); // recommended!
        $affiliate2 = $this->hoClient->get('Affiliate');
        $affiliate3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\Affiliate');
        $affiliate4 = new Affiliate();
        $affiliate4->setClient($this->hoClient);

        isClass(Affiliate::class, $affiliate1);
        isClass(Affiliate::class, $affiliate2);
        isClass(Affiliate::class, $affiliate3);
        isClass(Affiliate::class, $affiliate4);

        isNotSame($affiliate1, $affiliate2);
        isNotSame($affiliate1, $affiliate3);
    }

    public function testCanGetAffiliateById()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);

        is($this->testId, $affiliate->id);
    }

    public function testIsExist()
    {
        $affiliate = $this->hoClient->get(Affiliate::class);
        isFalse($affiliate->isExist());

        $affiliate = $this->hoClient->get(Affiliate::class, 0);
        isFalse($affiliate->isExist());

        $affiliate = $this->hoClient->get(Affiliate::class, '10000000');
        isFalse($affiliate->isExist());

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        isTrue($affiliate->isExist());
    }

    public function testUnset()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        isTrue($affiliate->city);
        unset($affiliate->city);
        isFalse($affiliate->city);

        isSame(['city' => null], $affiliate->getChangedFields());
    }

    public function testBindData()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $oldCity = $affiliate->city;

        $affiliate->mergeData(['city' => 'New city']);

        isNotSame($affiliate->city, $oldCity);
        isSame(['city' => 'New city'], $affiliate->getChangedFields());
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\Affiliate" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\Affiliate
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        is($this->testId, $affiliate->id);

        $affiliate->undefined_property;
    }

    public function testData()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        isNotEmpty($affiliate->data());
    }

    public function testIsset()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        isTrue(isset($affiliate->status));
        isFalse(isset($affiliate->undefined));
    }

    /**
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedContain()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);

        $affiliate->getFakeContainObject();
    }

    public function testGetAffiliateSignUpAnswers()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $answers = $affiliate->getAnswers();

        isSame(1, count($answers));
        isSame('What language do you speak?', $answers[0]['question']);
        isSame('English', $answers[0]['answer']);
    }

    public function testGetAffiliateUser()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $users = $affiliate->getAffiliateUser()->getList();

        isSame('2', $users->find('0.id'));
    }

    public function testCanCreateAffiliate()
    {
        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->company = $this->faker->company;
        $affiliate->phone = $this->faker->phoneNumber;
        isTrue($affiliate->isNew());
        $affiliate->save();
        isFalse($affiliate->isNew());

        /** @var Affiliate $affiliateCheck */
        $affiliateCheck = $this->hoClient->get(Affiliate::class, $affiliate->id);

        isSame($affiliate->id, $affiliateCheck->id); // Check is new id bind to object
        isSame($affiliate->company, $affiliateCheck->company);
        isSame($affiliate->phone, $affiliateCheck->phone);

        $affiliate->delete(); // Clean up after test
    }

    public function testCanUpdateAffiliate()
    {
        $this->skipIfFakeServer();

        $affiliateBeforeSave = $this->hoClient->get(Affiliate::class, $this->testId);

        $beforeCompany = $affiliateBeforeSave->company;
        $affiliateBeforeSave->company = $this->faker->company;
        $affiliateBeforeSave->save();

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, $this->testId);
        isNotSame($beforeCompany, $affiliateAfterSave->company);
    }

    public function testCanDeleteAffiliate()
    {
        $this->skipIfFakeServer();

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->delete();

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, $this->testId);

        isSame(Affiliate::STATUS_DELETED, $affiliateAfterSave->status);
    }

    public function testCanBlockAffiliate()
    {
        $this->skipIfFakeServer();

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->block();

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, $this->testId);
        isSame(Affiliate::STATUS_BLOCKED, $affiliateAfterSave->status);
    }

    public function testCanUnblockAffiliate()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->unblock();

        isSame(Affiliate::STATUS_ACTIVE, $affiliate->status);

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, $this->testId);
        isSame(Affiliate::STATUS_ACTIVE, $affiliateAfterSave->status);
    }

    public function testUpdateOnlyChangedFields()
    {
        $randomValue = $this->faker->email;

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->company = $randomValue;
        $affiliate->phone = $randomValue;

        isSame([
            'company' => $randomValue,
            'phone'   => $randomValue,
        ], $affiliate->getChangedFields());

        $affiliate->save();

        isSame([], $affiliate->getChangedFields());
    }

    public function testNoDataToUpdateIsNotError()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->save();

        isSame([], $affiliate->getChangedFields());
        is($this->testId, $affiliate->id);
    }

    public function testNoRequestOnEmptyDataSave()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->save();

        isSame([], $affiliate->getChangedFields());
        isSame(['ho.affiliate.save.before'], $eventChecker);
    }

    public function testNoChangeStatOnSameValues()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->reload();

        isSame([], $affiliate->getChangedFields());
        $affiliate->save();

        isSame(['ho.affiliate.save.before'], $eventChecker);
    }

    public function testNoChangeStatOnSameValuesAfterSet()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $company = $affiliate->company;
        $affiliate->company = $company;
        isSame([], $affiliate->getChangedFields());

        $affiliate->save();
        isSame(['ho.affiliate.save.before'], $eventChecker);
    }

    public function testSaveByArgument()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $newCompany = $this->faker->company;

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->save(['company' => $newCompany]);

        isSame([
            'ho.affiliate.save.before',
            'ho.affiliate.save.after',
        ], $eventChecker);
    }

    public function testNoSaveByArgumentWithSameProps()
    {
        $eventChecker = [];
        $this->eManager
            ->on('ho.*.save.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            })
            ->on('ho.api.request.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            });

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->save(['company' => $affiliate->company]);

        isSame([
            'ho.api.request.before',
            'ho.api.request.after',
            'ho.affiliate.save.before',
        ], $eventChecker);
    }
}
