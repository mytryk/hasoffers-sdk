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
use Unilead\HasOffers\Entity\AdvertiserInvoiceItem;

/**
 * Class AdvertiserInvoiceItemsTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserInvoiceItemsTest extends HasoffersPHPUnit
{
    public function testCanGetItemsByInvoiceId()
    {
        $someId = '36';
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $someId);

        $items = $invoice->getAdvertiserInvoiceItem()->getList();

        foreach ($items as $item) {
            is($someId, $item->invoice_id);
        }
    }

    public function testCanCreateInvoiceItem()
    {
        $invoiceId = 36;
        $rand = random_int(1, 500);
        $memo = 'Test Bill Item';
        $type = 'stats';

        /** @var AdvertiserInvoiceItem $invoiceItem */
        $invoiceItem = $this->hoClient->get(AdvertiserInvoiceItem::class);
        $invoiceItem->invoice_id = $invoiceId;
        $invoiceItem->offer_id = 8;
        $invoiceItem->memo = $memo;
        $invoiceItem->actions = $rand;
        $invoiceItem->amount = $rand;
        $invoiceItem->type = $type;
        $invoiceItem->revenue_type = 'cpa_flat';
        $invoiceItem->create();

        /** @var AdvertiserInvoice $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $item = $invoiceCheck->getAdvertiserInvoiceItem()->getItemById($invoiceItem->id);

        isNotSame(false, $item);
        isSame((string)$rand, $item->actions);
        isSame($memo, $item->memo);
        isSame($type, $item->type);
    }

    public function testCanDeleteInvoiceItem()
    {
        $invoiceId = 36;
        //get invoice items
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $items = $invoice->getAdvertiserInvoiceItem()->getList();

        // find last added and delete it
        $lastAddedItem = end($items);
        $lastAddedId = $lastAddedItem->id;
        $lastAddedItem->delete();

        //get invoice items again
        /** @var AdvertiserInvoice $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);

        //check item is not among them
        $notExistenItem = $invoiceCheck->getAdvertiserInvoiceItem()->getItemById($lastAddedId);
        isSame(false, $notExistenItem);
    }
}
