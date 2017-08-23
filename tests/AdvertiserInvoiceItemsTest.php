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

        $items = $invoice->getAdvertiserInvoiceItem()->data();

        is($someId, $items->find('0.invoice_id'));
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

        /** @var AdvertiserInvoiceItem $invoiceCheck */
        $invoiceCheck = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);

        $items = $invoiceCheck->getAdvertiserInvoiceItem()->data()->getArrayCopy();

        $itemKey = array_search((string)$invoiceItem->id[0], array_column($items, 'id'), true);
        isNotSame(false, $itemKey);
        isSame((string)$rand, $items[$itemKey]['actions']);
        isSame($memo, $items[$itemKey]['memo']);
        isSame($type, $items[$itemKey]['type']);
    }

    public function testCanDeleteInvoiceItem()
    {
        $invoiceId = 36;
        //get invoice items
        /** @var AdvertiserInvoice $bill */
        $bill = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $items = $bill->getAdvertiserInvoiceItem()->data();

        // find last added and delete it
        $lastAddedId = end($items)['id'];
        /** @var AdvertiserInvoiceItem $billItem */
        $billItem = $this->hoClient->get(AdvertiserInvoiceItem::class);
        $billItem->delete($lastAddedId);

        //get invoice items again
        /** @var AdvertiserInvoice $billCheck */
        $billCheck = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $itemsCheck = $billCheck->getAdvertiserInvoiceItem()->data()->getArrayCopy();

        //check item is not among them
        $itemKey = array_search((string)$lastAddedId, array_column($itemsCheck, 'id'), true);
        isSame(false, $itemKey);
    }
}
