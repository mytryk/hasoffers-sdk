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
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Contain\PaymentMethod;

/**
 * Class AffiliatePaymentMethodTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliatePaymentMethodTest extends HasoffersPHPUnit
{
    /**
     * @todo Refactor test, think about idempotence
     */
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

    public function testIsset()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $paymentMethod = $affiliate->getPaymentMethod();

        isTrue(isset($paymentMethod->affiliate_id));
        isFalse(isset($paymentMethod->undefined));
    }

    public function testGetParent()
    {
        $someId = '1004';
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $paymentMethod = $affiliate->getPaymentMethod();

        isSame($paymentMethod->getParent(), $affiliate);
    }

    public function testSaveByArgument()
    {
        $someId = '1004';
        $newEmail = Email::random();

        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $paymentMethod = $affiliate->getPaymentMethod();

        $paymentMethod->setType(PaymentMethod::TYPE_PAYPAL);
        isTrue($paymentMethod->save(['email' => $newEmail]));

        $affiliateCheker = $this->hoClient->get(Affiliate::class, $someId);
        $paymentMethodChecker = $affiliateCheker->getPaymentMethod();
        isSame($newEmail, $paymentMethodChecker->email);
    }
}
