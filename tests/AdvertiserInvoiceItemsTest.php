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

        $items = $invoice->getItemsResultSet()->findAll();

        foreach ($items as $item) {
            is($someId, $item->invoice_id);
        }
    }

    public function testCanCreateInvoiceItem()
    {
        $invoiceId = 36;
        $rand = random_int(1, 500);
        $memo = 'Test Invoice Item';
        $type = 'stats';

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $invoiceItem = $invoice->getItemsResultSet()->addItem();
        $invoiceItem->invoice_id = $invoiceId;
        $invoiceItem->offer_id = 8;
        $invoiceItem->memo = $memo;
        $invoiceItem->actions = $rand;
        $invoiceItem->amount = $rand;
        $invoiceItem->type = $type;
        $invoiceItem->revenue_type = 'cpa_flat';
        $invoiceItem->save();

        $item = $invoice->getItemsResultSet()->findById($invoiceItem->id);

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
        $items = $invoice->getItemsResultSet()->findAll();

        // find last added and delete it
        $lastAddedItem = end($items);
        $lastAddedId = $lastAddedItem->id;
        $lastAddedItem->delete();

        //check item is not among them
        $notExistingItem = $invoice->getItemsResultSet()->findById($lastAddedId);
        isSame(false, $notExistingItem);
    }

    public function testCanUpdateInvoiceItem()
    {
        $invoiceId = 36;
        $rand = random_int(1, 500);
        $memo = 'Test Invoice Item';
        $type = 'stats';

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        // Add item
        $invoiceItem = $invoice
            ->getItemsResultSet()
            ->addItem([
                'invoice_id'   => $invoiceId,
                'offer_id'     => 8,
                'memo'         => $memo,
                'actions'      => $rand,
                'amount'       => $rand,
                'type'         => $type,
                'revenue_type' => 'cpa_flat'
            ])->save();
        $itemIdBeforeUpdate = $invoiceItem->id;

        // Update item
        $updatedMemo = "{$memo}: {$rand}";
        $invoiceItem->memo = $updatedMemo;
        $invoiceItem->save();
        $itemIdAfterUpdate = $invoiceItem->id;

        // Check fields
        isNotSame($itemIdBeforeUpdate, $itemIdAfterUpdate);
        isSame($updatedMemo, $invoiceItem->memo);

        // Delete item
        $invoiceItem->delete();
    }
}
