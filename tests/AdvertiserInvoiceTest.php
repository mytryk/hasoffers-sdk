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

use Unilead\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoiceTest
 * @package JBZoo\PHPUnit
 */
class AdvertiserInvoiceTest extends HasoffersPHPUnit
{
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
     * @expectedExceptionMessage Missing required argument: data
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
        $someId = '6';
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $someId);
        is($someId, $invoice->id);

        $invoice->undefined_property;
    }

    public function testCanGetAdvertiserInvoiceById()
    {
        $someId = '6';
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $someId);

        is($someId, $invoice->id);
    }

    public function testCanCreateAdvertiserInvoice()
    {
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class);
        $invoice->advertiser_id = 502;
        $invoice->start_date = '2017-05-01';
        $invoice->end_date = '2017-05-31';
        $invoice->save();

        /** @var AdvertiserInvoice $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AdvertiserInvoice::class, $invoice->id);

        isSame($invoice->id, $invoiceCheck->id);
        isSame($invoice->start_date, $invoiceCheck->start_date);
        isSame($invoice->end_date, $invoiceCheck->end_date);
    }

    public function testCanUpdateAdvertiserInvoice()
    {
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, 6);
        $invoice->currency = 'EUR';
        $invoice->memo = 'test';
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
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, 6);
        $invoice->delete();

        isSame(AdvertiserInvoice::STATUS_DELETED, $invoice->status);
    }
}
