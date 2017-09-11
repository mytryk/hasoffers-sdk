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

use Unilead\HasOffers\Contain\AffiliateInvoiceItem;
use Unilead\HasOffers\Entity\AffiliateInvoice;

/**
 * Class AffiliateInvoiceItemTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateInvoiceItemsTest extends HasoffersPHPUnit
{
    public function testCanGetItemsByInvoiceId()
    {
        $someId = 24;
        /** @var AffiliateInvoice $affiliateInvoice */
        $affiliateInvoice = $this->hoClient->get(AffiliateInvoice::class, $someId);
        $affiliateInvoice->reload();

        $items = $affiliateInvoice->getItemsList()->findAll();

        foreach ($items as $item) {
            is($someId, $item->invoice_id);
        }
    }

    public function testCanCreateInvoiceItem()
    {
        $billId = 56;
        $randActions = random_int(1, 500);
        $randAmount = random_int(1, 500);
        $memo = 'Test Item';
        $type = 'stats';

        /** @var AffiliateInvoice $affInvoice */
        $affInvoice = $this->hoClient->get(AffiliateInvoice::class, $billId);
        $affInvoiceItemsResultSet = $affInvoice->getItemsList();
        $affInvoiceItem = $affInvoiceItemsResultSet->addItem();
        $affInvoiceItem->invoice_id = $billId;
        $affInvoiceItem->offer_id = 8;
        $affInvoiceItem->memo = $memo;
        $affInvoiceItem->actions = $randActions;
        $affInvoiceItem->amount = $randAmount;
        $affInvoiceItem->type = $type;
        $affInvoiceItem->payout_type = AffiliateInvoiceItem::PAYOUT_TYPE_CPA_FLAT;
        $affInvoiceItem->save();

        $item = $affInvoiceItemsResultSet->findById($affInvoiceItem->id);

        isNotSame(false, $item);
        isSame((string)$randActions, (string)$item->actions);
        isSame($memo, $item->memo);
        isSame($type, $item->type);
    }

    public function testCanDeleteInvoiceItem()
    {
        $billId = 56;

        /** @var AffiliateInvoice $affInvoice */
        $affInvoice = $this->hoClient->get(AffiliateInvoice::class, $billId);
        $items = $affInvoice->getItemsList()->findAll();

        // find last added and delete it
        $lastAddedItem = end($items);
        $lastAddedId = $lastAddedItem->id;
        $lastAddedItem->delete();

        //check item is not among them
        $notExistingItem = $affInvoice->getItemsList()->findById($lastAddedId);
        isSame(false, $notExistingItem);
    }

    public function testCanUpdateInvoiceItem()
    {
        $billId = 56;
        $randActions = random_int(1, 500);
        $randAmount = random_int(1, 500);
        $memo = 'Test Invoice Item';
        $type = 'stats';

        /** @var AffiliateInvoice $affInvoice */
        $affInvoice = $this->hoClient->get(AffiliateInvoice::class, $billId);
        // Add item
        $invoiceItem = $affInvoice
            ->getItemsList()
            ->addItem([
                'invoice_id'  => $billId,
                'offer_id'    => 8,
                'memo'        => $memo,
                'actions'     => $randActions,
                'amount'      => $randAmount,
                'type'        => $type,
                'payout_type' => AffiliateInvoiceItem::PAYOUT_TYPE_CPA_FLAT
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
