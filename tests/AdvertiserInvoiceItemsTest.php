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

use Unilead\HasOffers\Contain\AdvertiserInvoiceItem;
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

        $items = $invoice->getItemsList()->findAll();

        foreach ($items as $item) {
            is($someId, $item->invoice_id);
        }
    }

    public function testCanCreateInvoiceItem()
    {
        $invoiceId = 36;
        $randActions = random_int(1, 500);
        $randAmount = random_int(1, 500);
        $memo = 'Test Invoice Item';
        $type = 'stats';

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $invoiceItem = $invoice->getItemsList()->addItem();
        $invoiceItem->invoice_id = $invoiceId;
        $invoiceItem->offer_id = 8;
        $invoiceItem->memo = $memo;
        $invoiceItem->actions = $randActions;
        $invoiceItem->amount = $randAmount;
        $invoiceItem->type = $type;
        $invoiceItem->revenue_type = AdvertiserInvoiceItem::REVENUE_TYPE_CPA_FLAT;
        $invoiceItem->save();

        $item = $invoice->getItemsList()->findById($invoiceItem->id);

        isNotSame(false, $item);
        isSame((string)$randActions, $item->actions);
        isSame($memo, $item->memo);
        isSame($type, $item->type);
    }

    public function testCanDeleteInvoiceItem()
    {
        $invoiceId = 36;
        //get invoice items
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        $items = $invoice->getItemsList()->findAll();

        // find last added and delete it
        $lastAddedItem = end($items);
        $lastAddedId = $lastAddedItem->id;
        $lastAddedItem->delete();

        //check item is not among them
        $notExistingItem = $invoice->getItemsList()->findById($lastAddedId);
        isSame(false, $notExistingItem);
    }

    public function testCanUpdateInvoiceItem()
    {
        $invoiceId = 36;
        $randActions = random_int(1, 500);
        $randAmount = random_int(1, 500);
        $memo = 'Test Invoice Item';
        $type = 'stats';

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $invoiceId);
        // Add item
        $invoiceItem = $invoice
            ->getItemsList()
            ->addItem([
                'invoice_id'   => $invoiceId,
                'offer_id'     => 8,
                'memo'         => $memo,
                'actions'      => $randActions,
                'amount'       => $randAmount,
                'type'         => $type,
                'revenue_type' => AdvertiserInvoiceItem::REVENUE_TYPE_CPA_FLAT
            ])->save();
        $itemIdBeforeUpdate = $invoiceItem->id;

        // Update item
        $updatedMemo = "{$memo}: {$randActions}";
        $invoiceItem->memo = $updatedMemo;
        $invoiceItem->save();
        $itemIdAfterUpdate = $invoiceItem->id;

        // Check fields
        isNotSame($itemIdBeforeUpdate, $itemIdAfterUpdate);
        isSame($updatedMemo, $invoiceItem->memo);
        isSame($type, $invoiceItem->type);
        isSame($randActions, $invoiceItem->actions);

        // Delete item
        $invoiceItem->delete();
    }
}
