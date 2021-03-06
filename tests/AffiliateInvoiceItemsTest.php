<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace JBZoo\PHPUnit;

use Item8\HasOffers\Contain\AffiliateInvoiceItem;
use Item8\HasOffers\Entity\Affiliate;
use Item8\HasOffers\Entity\AffiliateInvoice;

/**
 * Class AffiliateInvoiceItemTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateInvoiceItemsTest extends HasoffersPHPUnit
{
    protected $testId = '12';

    public function testCanCreateInvoiceItem()
    {
        $randActions = $this->faker->randomNumber(2) + 1;
        $randAmount = $this->faker->randomNumber(2) + 1;
        $memo = $this->faker->text();
        $type = 'stats';

        /** @var AffiliateInvoice $affInvoice */
        $affInvoice = $this->hoClient->get(AffiliateInvoice::class, $this->testId);
        $affInvoiceItemsResultSet = $affInvoice->getItemsList();
        $affInvoiceItem = $affInvoiceItemsResultSet->addItem();
        $affInvoiceItem->invoice_id = $this->testId;
        $affInvoiceItem->offer_id = 10;
        $affInvoiceItem->memo = $memo;
        $affInvoiceItem->actions = $randActions;
        $affInvoiceItem->amount = $randAmount;
        $affInvoiceItem->type = $type;
        $affInvoiceItem->payout_type = AffiliateInvoiceItem::PAYOUT_TYPE_CPA_FLAT;
        $affInvoiceItem->save();

        $affInvoiceItemsResultSet->findById($affInvoiceItem->id);

        $affInvoiceItemsResultSet->reload();

        $item = $affInvoiceItemsResultSet->findById($affInvoiceItem->id);

        isNotSame(false, $item);
        isSame((string)$randActions, (string)$item->actions);
        isSame($memo, $item->memo);
        isSame($type, $item->type);

        //$invoiceItem->delete(); // Clean up after test, but delete leter in tests
    }

    /**
     * @depends testCanCreateInvoiceItem
     */
    public function testCanGetItemsByInvoiceId()
    {
        /** @var AffiliateInvoice $affiliateInvoice */
        $affiliateInvoice = $this->hoClient->get(AffiliateInvoice::class, $this->testId);
        $affiliateInvoice->reload();

        $items = $affiliateInvoice->getItemsList()->findAll();
        isTrue(count($items) > 0);

        foreach ($items as $item) {
            is($this->testId, $item->invoice_id);
        }
    }

    public function testCanDeleteInvoiceItem()
    {
        $billId = $this->testId;

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
        $billId = $this->testId;
        $randActions = $this->faker->randomNumber(2) + 1;
        $randAmount = $this->faker->randomNumber(2) + 1;
        $memo = $this->faker->text(20);
        $type = 'stats';

        /** @var AffiliateInvoice $affInvoice */
        $affInvoice = $this->hoClient->get(AffiliateInvoice::class, $billId);
        // Add item
        $invoiceItem = $affInvoice
            ->getItemsList()
            ->addItem([
                'invoice_id'  => $billId,
                'offer_id'    => 10,
                'memo'        => $memo,
                'actions'     => $randActions,
                'amount'      => $randAmount,
                'type'        => $type,
                'payout_type' => AffiliateInvoiceItem::PAYOUT_TYPE_CPA_FLAT,
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

    public function testBindExcludedProps()
    {
        skip('Fix code for test');
        $text = $this->faker->text(30);

        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->reload();
        $affiliate->bindData([
            'address1' => $text,
            '_id'      => 123,
            'id'       => 123,
        ]);

        isSame(['address1' => $text], $affiliate->getChangedFields());
    }
}
