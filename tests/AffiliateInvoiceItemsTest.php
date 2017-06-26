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

use Unilead\HasOffers\Entity\AffiliateInvoice;
use Unilead\HasOffers\Entity\AffiliateInvoiceItem;

/**
 * Class AffiliateInvoiceItemTest
 * @package JBZoo\PHPUnit
 */
class AffiliateInvoiceItemsTest extends HasoffersPHPUnit
{
    //todo: get by invoice id
    //todo: add by invoice id
    //todo: remove by invoice id

    public function testCanGetItemsByInvoiceId()
    {
        skip('containt items does not work');
        $someId = '24';
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, $someId);

        dump($bill->InvoiceItem);

        is($someId, $bill->id);
    }

    public function testCanCreateInvoiceItem()
    {
        skip('containt items does not work');
        $rand = mt_rand(1262055681, 1262055681);

        /** @var AffiliateInvoiceItem $bill */
        $bill = $this->hoClient->get(AffiliateInvoiceItem::class);
        $bill->affiliate_id = 1004;
        $bill->start_date = date("Y-m-d H:i:s", $rand);
        $bill->end_date = date("Y-m-d H:i:s", $rand);
        $bill->save();

        /** @var AffiliateInvoiceItem $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AffiliateInvoiceItem::class, $bill->id);

        isSame($bill->id, $invoiceCheck->id);
        isSame($bill->start_date, $invoiceCheck->start_date);
        isSame($bill->end_date, $invoiceCheck->end_date);
    }

    public function testCanDeleteInvoiceItem()
    {
        skip('containt items does not work');
        /** @var AffiliateInvoiceItem $bill */
        $bill = $this->hoClient->get(AffiliateInvoiceItem::class, 22);
        $bill->delete();

        isSame(AffiliateInvoiceItem::STATUS_DELETED, $bill->status);
    }
}
