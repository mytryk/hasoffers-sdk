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
    public function testCanGetItemsByInvoiceId()
    {
        $someId = 24;
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, $someId);

        $items = $bill->getAffiliateInvoiceItem()->getRawData();

        is($someId, $items[0]['invoice_id']);
    }

    public function testCanCreateInvoiceItem()
    {
        $billId = 56;
        $rand = mt_rand(1, 500);
        $memo = 'Test Bill Item';
        $type = 'stats';

        /** @var AffiliateInvoiceItem $billItem */
        $billItem = $this->hoClient->get(AffiliateInvoiceItem::class);
        $billItem->invoice_id = $billId;
        $billItem->offer_id = 8;
        $billItem->memo = $memo;
        $billItem->actions = $rand;
        $billItem->amount = $rand;
        $billItem->type = $type;
        $billItem->payout_type = 'cpa_flat';
        $billItem->create();

        /** @var AffiliateInvoiceItem $billCheck */
        $billCheck = $this->hoClient->get(AffiliateInvoice::class, $billId);

        $items = $billCheck->getAffiliateInvoiceItem()->getRawData();

        $itemKey = array_search(strval($billItem->id[0]), array_column($items, 'id'));
        isNotSame(false, $itemKey);
        isSame(strval($rand), $items[$itemKey]['actions']);
        isSame($memo, $items[$itemKey]['memo']);
        isSame($type, $items[$itemKey]['type']);
    }

    public function testCanDeleteInvoiceItem()
    {
        //get bill items
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, 56);
        $items = $bill->getAffiliateInvoiceItem()->getRawData();

        //find first one and delete it
        /** @var AffiliateInvoiceItem $billItem */
        $billItem = $this->hoClient->get(AffiliateInvoiceItem::class);
        $billItem->delete($items[0]['id']);

        //get bill items again
        /** @var AffiliateInvoice $billCheck */
        $billCheck = $this->hoClient->get(AffiliateInvoice::class, 56);
        $itemsCheck = $billCheck->getAffiliateInvoiceItem()->getRawData();

        //check item is not among them
        $itemKey = array_search(strval($items[0]['id']), array_column($itemsCheck, 'id'));
        isSame(false, $itemKey);
    }
}
