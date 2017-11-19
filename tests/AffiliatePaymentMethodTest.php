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

use JBZoo\Data\Data;
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Contain\PaymentMethod;

/**
 * Class AffiliatePaymentMethodTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliatePaymentMethodTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        // Revert to Paypal method
        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
        $paymentMethod->email = $this->faker->email;
        $paymentMethod->save();
    }

    public function testGetAffiliatePaymentMethodType()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());

        $paymentRawData = $paymentMethod->data();
        isClass(Data::class, $paymentRawData);
        isSame($paymentRawData->email, $paymentMethod->email);
    }

    public function testCanUpdatePaymentDetails()
    {
        $randomEmail = $this->faker->email;

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());

        $paymentMethod->email = $randomEmail;
        isSame(['email' => $randomEmail], $paymentMethod->getChangedFields());
        isSame($randomEmail, $paymentMethod->email);
        isTrue($paymentMethod->save());
        isSame($randomEmail, $paymentMethod->email);

        // Check updated field
        $expAffiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $expPaymentMethod = $expAffiliate->getPaymentMethod();
        isSame(PaymentMethod::TYPE_PAYPAL, $expPaymentMethod->getType());
        isSame($randomEmail, $expPaymentMethod->email);
    }

    public function testCanChangePaymentMethodType()
    {
        $randomEmail = $this->faker->email;

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        $currentType = $paymentMethod->getType();
        if ($currentType === PaymentMethod::TYPE_PAYPAL) {
            $paymentMethod->setType(PaymentMethod::TYPE_OTHER);
            $paymentMethod->details = $randomEmail;
        } else {
            $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
            $paymentMethod->email = $randomEmail;
        }

        isTrue($paymentMethod->save());

        // Check updated field
        $expAffiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $expPaymentMethod = $expAffiliate->getPaymentMethod();
        isNotSame($currentType, $expPaymentMethod->getType());
    }

    public function testCanCreatePaymentMethod()
    {
        $randomEmail = $this->faker->email;

        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->company = $this->faker->company;
        $affiliate->phone = $this->faker->phoneNumber;
        $affiliate->save();
        isTrue($affiliate->id > 0);

        $paymentMethod = $affiliate->getPaymentMethod();
        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);

        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());

        $paymentMethod->email = $randomEmail;
        isSame($randomEmail, $paymentMethod->email);
        $paymentMethod->save();
        isSame($randomEmail, $paymentMethod->email);

        // Check updated field
        /** @var Affiliate $expAffiliate */
        $expAffiliate = $this->hoClient->get(Affiliate::class, $affiliate->id);
        $expPaymentMethod = $expAffiliate->getPaymentMethod();
        isSame($randomEmail, $expPaymentMethod->email);
        isSame(PaymentMethod::TYPE_PAYPAL, $expPaymentMethod->getType());
        isSame($expAffiliate->id, $affiliate->id);
    }

    public function testIsset()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        isTrue(isset($paymentMethod->affiliate_id));
        isFalse(isset($paymentMethod->undefined));
    }

    public function testGetParent()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        isSame($paymentMethod->getParent(), $affiliate);
    }

    public function testUnsetProp()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();
        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
        $paymentMethod->save([
            'email' => $this->faker->email,
        ]);

        isTrue(isset($paymentMethod->email));
        unset($paymentMethod->email);
        isNull($paymentMethod->email);
        isSame(['email' => null], $paymentMethod->getChangedFields());
    }

    public function testBindExcludedProps()
    {
        skip('Fix bindData for PaymentMethod');

        $newEmail = $this->faker->email;

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();
        $paymentMethod->bindData([
            'id'    => 123,
            '_prop' => 123,
            'email' => $newEmail,
        ]);

        isSame(['email' => $newEmail], $paymentMethod->getChangedFields());
    }

    public function testSaveByArgument()
    {
        $eventChecker = [];
        $this->eManager
            ->on('ho.paymentmethod.save.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            });

        $randomEmail = $this->faker->email;

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
        isTrue($paymentMethod->save(['email' => $randomEmail]));

        $affiliateCheker = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethodChecker = $affiliateCheker->getPaymentMethod();
        isSame($randomEmail, $paymentMethodChecker->email);

        isSame([
            'ho.paymentmethod.save.before',
            'ho.paymentmethod.save.after',
        ], $eventChecker);
    }

    public function testNoSaveByArgumentOnSetSameValues()
    {
        //skip('Don\'t support mode "no changes = no request to HO"');
        $eventChecker = [];
        $this->eManager
            ->on('ho.paymentmethod.save.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            });

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();

        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
        isTrue($paymentMethod->save(['email' => $paymentMethod->email])); // should be isFalse

        $affiliateCheker = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethodChecker = $affiliateCheker->getPaymentMethod();
        isSame($paymentMethod->email, $paymentMethodChecker->email);
    }
}
