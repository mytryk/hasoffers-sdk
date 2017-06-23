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

use Unilead\HasOffers\Entity\AdvertiserInvoiceItem;

/**
 * Class AffiliateInvoiceTest
 * @package JBZoo\PHPUnit
 */
class AffiliateInvoiceTest extends HasoffersPHPUnit
{
    public function testCreatingAffiliateInvoiceWays()
    {
        skip();
        $bill1 = $this->hoClient->get(AdvertiserInvoiceItem::class); // recommended!
        $bill2 = $this->hoClient->get('AffiliateInvoice');
        $bill3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\AdvertiserInvoiceItem');
        $bill4 = new AdvertiserInvoiceItem();
        $bill4->setClient($this->hoClient);

        isClass(AdvertiserInvoiceItem::class, $bill1);
        isClass(AdvertiserInvoiceItem::class, $bill2);
        isClass(AdvertiserInvoiceItem::class, $bill3);
        isClass(AdvertiserInvoiceItem::class, $bill4);

        isNotSame($bill1, $bill2);
        isNotSame($bill1, $bill3);
    }

    /**
     * @expectedExceptionMessage Missing required argument: data
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        skip();
        $bill = $this->hoClient->get(AdvertiserInvoiceItem::class);
        $bill->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\AffiliateInvoice
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        skip();
        $someId = '22';
        /** @var AdvertiserInvoiceItem $bill */
        $bill = $this->hoClient->get(AdvertiserInvoiceItem::class, $someId);
        is($someId, $bill->id);

        $bill->undefined_property;
    }

    public function testCanGetAffiliateInvoiceById()
    {
        skip();
        $someId = '22';
        /** @var AdvertiserInvoiceItem $bill */
        $bill = $this->hoClient->get(AdvertiserInvoiceItem::class, $someId);

        is($someId, $bill->id);
    }

    public function testCanCreateAffiliateInvoice()
    {
        skip();
        $rand = mt_rand(1262055681, 1262055681);

        /** @var AdvertiserInvoiceItem $bill */
        $bill = $this->hoClient->get(AdvertiserInvoiceItem::class);
        $bill->affiliate_id = 1004;
        $bill->start_date = date("Y-m-d H:i:s", $rand);
        $bill->end_date = date("Y-m-d H:i:s", $rand);
        $bill->save();

        /** @var AdvertiserInvoiceItem $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AdvertiserInvoiceItem::class, $bill->id);

        isSame($bill->id, $invoiceCheck->id);
        isSame($bill->start_date, $invoiceCheck->start_date);
        isSame($bill->end_date, $invoiceCheck->end_date);
    }

    public function testCanUpdateAffiliateInvoice()
    {
        skip();
        /** @var AdvertiserInvoiceItem $bill */
        $bill = $this->hoClient->get(AdvertiserInvoiceItem::class, 22);
        $bill->currency = 'EUR';
        $bill->memo = 'test';
        $bill->status = AdvertiserInvoiceItem::STATUS_ACTIVE;
        $bill->save();

        /** @var AdvertiserInvoiceItem $billCheck */
        $billCheck = $this->hoClient->get(AdvertiserInvoiceItem::class, $bill->id);

        isSame($bill->id, $billCheck->id);
        isSame($bill->currency, $billCheck->currency);
        isSame($bill->memo, $billCheck->memo);
    }

    public function testCanDeleteAffiliateInvoice()
    {
        skip();
        /** @var AdvertiserInvoiceItem $bill */
        $bill = $this->hoClient->get(AdvertiserInvoiceItem::class, 22);
        $bill->delete();

        isSame(AdvertiserInvoiceItem::STATUS_DELETED, $bill->status);
    }
}
