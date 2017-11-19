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

use Item8\HasOffers\Entity\AffiliateInvoice;

/**
 * Class AffiliateInvoiceTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateInvoiceTest extends HasoffersPHPUnit
{
    protected $testId = '4';

    public function testCreatingAffiliateInvoiceWays()
    {
        $bill1 = $this->hoClient->get(AffiliateInvoice::class); // recommended!
        $bill2 = $this->hoClient->get('AffiliateInvoice');
        $bill3 = $this->hoClient->get('Item8\\HasOffers\\Entity\\AffiliateInvoice');
        $bill4 = new AffiliateInvoice();
        $bill4->setClient($this->hoClient);

        isClass(AffiliateInvoice::class, $bill1);
        isClass(AffiliateInvoice::class, $bill2);
        isClass(AffiliateInvoice::class, $bill3);
        isClass(AffiliateInvoice::class, $bill4);

        isNotSame($bill1, $bill2);
        isNotSame($bill1, $bill3);
    }

    /**
     * @expectedExceptionMessage No data to create new object "Item8\HasOffers\Entity\AffiliateInvoice" in HasOffers
     * @expectedException        \Item8\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $bill = $this->hoClient->get(AffiliateInvoice::class);
        $bill->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Item8\HasOffers\Entity\AffiliateInvoice
     * @expectedException \Item8\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, $this->testId);
        is($this->testId, $bill->id);

        $bill->undefined_property;
    }

    public function testCanGetAffiliateInvoiceById()
    {
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, $this->testId);

        is($this->testId, $bill->id);
    }

    public function testCanCreateAffiliateInvoice()
    {
        $rand = random_int(1262055681, 1262055681);

        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class);
        $bill->affiliate_id = 1004;
        $bill->start_date = date('Y-m-d H:i:s', $rand);
        $bill->end_date = date('Y-m-d H:i:s', $rand);
        $bill->save();

        /** @var AffiliateInvoice $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AffiliateInvoice::class, $bill->id);

        isSame($bill->id, $invoiceCheck->id);
        isSame($bill->start_date, $invoiceCheck->start_date);
        isSame($bill->end_date, $invoiceCheck->end_date);

        $bill->delete(); // Clean up after test
    }

    public function testCanUpdateAffiliateInvoice()
    {
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, $this->testId);
        $bill->currency = $this->faker->currencyCode;
        $bill->memo = $this->faker->sentence();
        $bill->status = AffiliateInvoice::STATUS_ACTIVE;
        $bill->save();

        /** @var AffiliateInvoice $billCheck */
        $billCheck = $this->hoClient->get(AffiliateInvoice::class, $bill->id);

        isSame($bill->id, $billCheck->id);
        isSame($bill->currency, $billCheck->currency);
        isSame($bill->memo, $billCheck->memo);
    }

    public function testCanDeleteAffiliateInvoice()
    {
        /** @var AffiliateInvoice $billReset */
        $billReset = $this->hoClient->get(AffiliateInvoice::class, $this->testId);
        $billReset->status = AffiliateInvoice::STATUS_ACTIVE;
        $billReset->save();

        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, $this->testId);
        $bill->delete();

        isSame(AffiliateInvoice::STATUS_DELETED, $bill->status);
    }
}
