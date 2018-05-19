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

use Item8\HasOffers\Contain\AdvertiserInvoiceItem;
use Item8\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoiceItemsTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserInvoiceItemsTest extends HasoffersPHPUnit
{
    protected $testId = '22';

    public function testCanCreateInvoiceItem()
    {
        $randActions = random_int(1, 500);
        $randAmount = random_int(1, 500);
        $memo = $this->faker->text();
        $type = 'stats';

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);
        $invoiceItem = $invoice->getItemsList()->addItem();
        $invoiceItem->invoice_id = $this->testId;
        $invoiceItem->offer_id = 8;
        $invoiceItem->memo = $memo;
        $invoiceItem->actions = $randActions;
        $invoiceItem->amount = $randAmount;
        $invoiceItem->type = $type;
        $invoiceItem->revenue_type = AdvertiserInvoiceItem::REVENUE_TYPE_CPA_FLAT;
        $invoiceItem->save();

        $invoice->getItemsList()->findById($invoiceItem->id);

        $invoiceItem->reload();
        $item = $invoice->getItemsList()->findById($invoiceItem->id);

        isNotSame(false, $item);
        isSame((string)$randActions, $item->actions);
        isSame($memo, $item->memo);
        isSame($type, $item->type);

        $invoiceItem->delete(); // Clean up after test, but delete leter in tests
    }

    public function testCanGetItemsByInvoiceId()
    {
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);

        $items = $invoice->getItemsList()->findAll();

        isTrue(count($items) > 0);

        foreach ($items as $item) {
            is($this->testId, $item->invoice_id);
        }
    }

    public function testCanDeleteInvoiceItem()
    {
        //get invoice items
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);
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
        $randActions = $this->faker->randomNumber(2) + 1;
        $randAmount = $this->faker->randomNumber(2) + 1;
        $memo = $this->faker->text();
        $type = 'stats';

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);
        // Add item
        $invoiceItem = $invoice
            ->getItemsList()
            ->addItem([
                'invoice_id'   => $this->testId,
                'offer_id'     => 8,
                'memo'         => $memo,
                'actions'      => $randActions,
                'amount'       => $randAmount,
                'type'         => $type,
                'revenue_type' => AdvertiserInvoiceItem::REVENUE_TYPE_CPA_FLAT,
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
