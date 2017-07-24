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

use JBZoo\Data\Data;
use JBZoo\Utils\Email;
use JBZoo\Utils\Str;
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Contain\PaymentMethod;
use Unilead\HasOffers\Entity\AffiliateUser;

/**
 * Class AffiliateTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateTest extends HasoffersPHPUnit
{
    public function testEventManagerAttach()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);

        $checkerCounter = 0;
        $this->eManager->on('ho.Affiliate.reload.*', function () use (&$checkerCounter) {
            $checkerCounter++;
        });

        $affiliate->reload();

        isSame(2, $checkerCounter);
    }

    public function testLimitOption()
    {
        $this->hoClient->setTimeout(5);
        $this->hoClient->setRequestsLimit(2);

        $startTime = time();
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->reload();
        $affiliate->reload();
        $affiliate->reload();
        $affiliate->reload();
        $finishTime = time();

        isTrue($finishTime - $startTime > 9, 'Timeout is ' . ($finishTime - $startTime));
    }

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

    public function testGetAffiliateSignUpAnswers()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $answers = $affiliate->getAnswers();

        isSame(2, count($answers));
        isSame('What language do you speak?', $answers[1]['question']);
        isSame('English', $answers[1]['answer']);
    }

    public function testGetAffiliatePaymentMethodType()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $paymentMethod = $affiliate->getPaymentMethod();

        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());

        $paymentRawData = $paymentMethod->data();
        isClass(Data::class, $paymentRawData);
        isSame($paymentRawData->email, $paymentMethod->email);
    }

    public function testGetAffiliateUser()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $users = $affiliate->getAffiliateUser()->getList();

        isSame('10', $users[0]['id']);
        isSame('anbelov83@belov.ru', $users[0]['email']);
        isSame(AffiliateUser::STATUS_DELETED, $users[0]['status']);
    }

    public function testCanCreateAffiliate()
    {
        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->company = 'Test Company';
        $affiliate->phone = '+7 845 845 84 54';
        $affiliate->save();

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

        is(1004, $affiliate->id);
    }

    public function testCanUpdatePaymentMethod()
    {
        $someId = '1004';
        $randomEmail = Email::random();

        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $paymentMethod = $affiliate->getPaymentMethod();

        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());

        $paymentMethod->email = $randomEmail;
        isSame($randomEmail, $paymentMethod->email);
        isTrue($paymentMethod->save());
        isSame($randomEmail, $paymentMethod->email);

        // Check updated field
        $expAffiliate = $this->hoClient->get(Affiliate::class, $someId);
        $expPaymentMethod = $expAffiliate->getPaymentMethod();
        isSame(PaymentMethod::TYPE_PAYPAL, $expPaymentMethod->getType());
        isSame($randomEmail, $expPaymentMethod->email);
    }

    public function testCanCreatePaymentMethod()
    {
        $randomEmail = Email::random();

        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->company = 'Test Company';
        $affiliate->phone = '+7 845 845 84 54';
        $affiliate->save();
        isTrue($affiliate->id > 0);

        $paymentMethod = $affiliate->getPaymentMethod();
        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);

        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());

        $paymentMethod->email = $randomEmail;
        isSame($randomEmail, $paymentMethod->email);
        isTrue($paymentMethod->save());
        isSame($randomEmail, $paymentMethod->email);

        // Check updated field
        /** @var Affiliate $expAffiliate */
        $expAffiliate = $this->hoClient->get(Affiliate::class, $affiliate->id);
        $expPaymentMethod = $expAffiliate->getPaymentMethod();
        isSame($randomEmail, $expPaymentMethod->email);
        isSame(PaymentMethod::TYPE_PAYPAL, $expPaymentMethod->getType());
        isSame($expAffiliate->id, $affiliate->id);
    }
}
