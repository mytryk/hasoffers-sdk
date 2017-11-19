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

use Unilead\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoiceTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserInvoiceTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testCreatingAdvertiserInvoiceWays()
    {
        $invoice1 = $this->hoClient->get(AdvertiserInvoice::class); // recommended!
        $invoice2 = $this->hoClient->get('AdvertiserInvoice');
        $invoice3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\AdvertiserInvoice');
        $invoice4 = new AdvertiserInvoice();
        $invoice4->setClient($this->hoClient);

        isClass(AdvertiserInvoice::class, $invoice1);
        isClass(AdvertiserInvoice::class, $invoice2);
        isClass(AdvertiserInvoice::class, $invoice3);
        isClass(AdvertiserInvoice::class, $invoice4);

        isNotSame($invoice1, $invoice2);
        isNotSame($invoice1, $invoice3);
    }

    /**
     * @expectedExceptionMessage No data to create new object "Unilead\HasOffers\Entity\AdvertiserInvoice" in HasOffers
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $invoice = $this->hoClient->get(AdvertiserInvoice::class);
        $invoice->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\AdvertiserInvoice
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $someId = $this->testId;
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $someId);
        is($someId, $invoice->id);

        $invoice->undefined_property;
    }

    public function testCanGetAdvertiserInvoiceById()
    {
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);

        is($this->testId, $invoice->id);
    }

    public function testCanCreateAdvertiserInvoice()
    {
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class);
        $invoice->advertiser_id = '500';
        $invoice->start_date = $this->faker->date();
        $invoice->end_date = $this->faker->date();
        $invoice->save();

        /** @var AdvertiserInvoice $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AdvertiserInvoice::class, $invoice->id);

        isSame($invoice->id, $invoiceCheck->id);
        isSame($invoice->start_date, $invoiceCheck->start_date);
        isSame($invoice->end_date, $invoiceCheck->end_date);

        $invoice->delete(); // Clean up after test
    }

    public function testCanUpdateAdvertiserInvoice()
    {
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);
        $invoice->currency = $this->faker->currencyCode;
        $invoice->memo = $this->faker->realText();
        $invoice->status = AdvertiserInvoice::STATUS_ACTIVE;
        $invoice->save();

        /** @var AdvertiserInvoice $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AdvertiserInvoice::class, $invoice->id);

        isSame($invoice->id, $invoiceCheck->id);
        isSame($invoice->currency, $invoiceCheck->currency);
        isSame($invoice->memo, $invoiceCheck->memo);
    }

    public function testCanDeleteAdvertiserInvoice()
    {
        $invoiceId = $this->testId;
        /** @var AdvertiserInvoice $invoiceReset */
        $invoiceReset = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        if ($invoiceReset->status !== AdvertiserInvoice::STATUS_ACTIVE) {
            $invoiceReset->status = AdvertiserInvoice::STATUS_ACTIVE;
            $invoiceReset->save();
        }

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $invoice->delete();

        isSame(AdvertiserInvoice::STATUS_DELETED, $invoice->status);
    }
}
