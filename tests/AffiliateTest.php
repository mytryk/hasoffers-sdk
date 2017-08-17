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
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliateTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateTest extends HasoffersPHPUnit
{
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
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);

        is($someId, $affiliate->id);
    }

    public function testIsExist()
    {
        $affiliate = $this->hoClient->get(Affiliate::class);
        isFalse($affiliate->isExist());

        $affiliate = $this->hoClient->get(Affiliate::class, 0);
        isFalse($affiliate->isExist());

        $affiliate = $this->hoClient->get(Affiliate::class, '10000000');
        isFalse($affiliate->isExist());

        $affiliate = $this->hoClient->get(Affiliate::class, '1004');
        isTrue($affiliate->isExist());
    }

    public function testUnset()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, '1004');
        isTrue($affiliate->city);
        unset($affiliate->city);
        isFalse($affiliate->city);

        isSame(['city' => null], $affiliate->getChangedFields());
    }

    public function testBindData()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, '1004');
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
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        is($someId, $affiliate->id);

        $affiliate->undefined_property;
    }

    public function testData()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        isNotEmpty($affiliate->data());
    }

    public function testIsset()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        isTrue(isset($affiliate->status));
        isFalse(isset($affiliate->undefined));
    }

    /**
     * @expectedExceptionMessage Undefined method "getFakeContainObject" or related object "FakeContainObject" in
     *                           Unilead\HasOffers\Entity\Affiliate for objectId=1004
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedContain()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);

        $affiliate->getFakeContainObject();
    }

    public function testGetAffiliateSignUpAnswers()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $answers = $affiliate->getAnswers();

        isSame(2, count($answers));
        isSame('What language do you speak?', $answers[1]['question']);
        isSame('English', $answers[1]['answer']);
    }

    public function testGetAffiliateUser()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $users = $affiliate->getAffiliateUser()->getList();

        isSame('10', $users->find('0.id'));
        isSame('anbelov83@belov.ru', $users->find('0.email'));
        isSame(AffiliateUser::STATUS_DELETED, $users->find('0.status'));
    }

    public function testCanCreateAffiliate()
    {
        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->company = 'Test Company';
        $affiliate->phone = '+7 845 845 84 54';
        isTrue($affiliate->isNew());
        $affiliate->save();
        isFalse($affiliate->isNew());

        /** @var Affiliate $affiliateCheck */
        $affiliateCheck = $this->hoClient->get(Affiliate::class, $affiliate->id);

        isSame($affiliate->id, $affiliateCheck->id); // Check is new id bind to object
        isSame($affiliate->company, $affiliateCheck->company);
        isSame($affiliate->phone, $affiliateCheck->phone);
    }

    public function testCanUpdateAffiliate()
    {
        $this->skipIfFakeServer();

        $affiliateBeforeSave = $this->hoClient->get(Affiliate::class, 1004);

        $beforeCompany = $affiliateBeforeSave->company;
        $affiliateBeforeSave->company = Str::random();
        $affiliateBeforeSave->save();

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, 1004);
        isNotSame($beforeCompany, $affiliateAfterSave->company);
    }

    public function testCanDeleteAffiliate()
    {
        $this->skipIfFakeServer();

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->delete();

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, 1004);

        isSame(Affiliate::STATUS_DELETED, $affiliateAfterSave->status);
    }

    public function testCanBlockAffiliate()
    {
        $this->skipIfFakeServer();

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->block();

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, 1004);
        isSame(Affiliate::STATUS_BLOCKED, $affiliateAfterSave->status);
    }

    public function testCanUnblockAffiliate()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->unblock();

        isSame(Affiliate::STATUS_ACTIVE, $affiliate->status);

        $affiliateAfterSave = $this->hoClient->get(Affiliate::class, 1004);
        isSame(Affiliate::STATUS_ACTIVE, $affiliateAfterSave->status);
    }

    public function testUpdateOnlyChangedFields()
    {
        $randomValue = Str::random();

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
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
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->save();

        isSame([], $affiliate->getChangedFields());
        is(1004, $affiliate->id);
    }

    public function testNoRequestOnEmptyDataSave()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
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

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
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

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
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

        $newCompany = Str::random();

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
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

        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->save(['company' => $affiliate->company]);

        isSame([
            'ho.api.request.before',
            'ho.api.request.after',
            'ho.affiliate.save.before',
        ], $eventChecker);
    }
}
