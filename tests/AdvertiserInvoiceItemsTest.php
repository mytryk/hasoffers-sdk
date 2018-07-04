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
        $offerId = '8';

        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);
        $invoiceItem = $invoice->getItemsList()->addItem();
        $invoiceItem->invoice_id = $this->testId;
        $invoiceItem->offer_id = $offerId;
        $invoiceItem->memo = $memo;
        $invoiceItem->actions = $randActions;
        $invoiceItem->amount = $randAmount;
        $invoiceItem->type = $type;
        $invoiceItem->revenue_type = AdvertiserInvoiceItem::REVENUE_TYPE_CPA_FLAT;
        $invoiceItem->save();

        $invoice->getItemsList()->findById($invoiceItem->id);

        $invoiceItem->reload();
        /** @var AdvertiserInvoiceItem $item **/
        $item = $invoice->getItemsList()->findById($invoiceItem->id);

        isNotSame(false, $item);
        isSame((string)$randActions, $item->actions);
        isSame((float)$randAmount, (float)$item->amount);
        isSame($this->testId, $item->invoice_id);
        isSame($offerId, $item->offer_id);
        isSame(AdvertiserInvoiceItem::REVENUE_TYPE_CPA_FLAT, $item->revenue_type);
        isSame($memo, $item->memo);
        isSame($type, $item->type);

        $invoiceItem->delete(); // Clean up after test, but delete later in tests
    }

    /**
     * @depends testCanCreateInvoiceItem
     */
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

    /**
     * @depends testCanGetItemsByInvoiceId
     */
    public function testCanDeleteInvoiceItem()
    {
        // Get invoice items
        /** @var AdvertiserInvoice $invoice */
        $invoice = $this->hoClient->get(AdvertiserInvoice::class, $this->testId);
        $items = $invoice->getItemsList()->findAll();

        // Find last added and delete it
        $lastAddedItem = end($items);
        $lastAddedId = $lastAddedItem->id;
        $lastAddedItem->delete();

        // Check item is not among them
        $notExistingItem = $invoice->getItemsList()->findById($lastAddedId);
        isSame(false, $notExistingItem);

        // Clean up. Create new item for future tests.
        $this->createNewItem($invoice);
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

    /**
     * @param AdvertiserInvoice $invoice
     */
    protected function createNewItem(AdvertiserInvoice $invoice)
    {
        $randActions = random_int(1, 500);
        $randAmount = random_int(1, 500);
        $memo = $this->faker->text();
        $type = 'stats';
        $offerId = '8';

        $invoiceItem = $invoice->getItemsList()->addItem();
        $invoiceItem->invoice_id = $this->testId;
        $invoiceItem->offer_id = $offerId;
        $invoiceItem->memo = $memo;
        $invoiceItem->actions = $randActions;
        $invoiceItem->amount = $randAmount;
        $invoiceItem->type = $type;
        $invoiceItem->revenue_type = AdvertiserInvoiceItem::REVENUE_TYPE_CPA_FLAT;
        $invoiceItem->save();
    }
}
