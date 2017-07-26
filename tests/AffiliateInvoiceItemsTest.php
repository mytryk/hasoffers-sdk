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

        $items = $affiliateInvoice->getAffiliateInvoiceItem()->data();

        is($someId, $items[0]['invoice_id']);
    }

    public function testCanCreateInvoiceItem()
    {
        $billId = 56;
        $rand = random_int(1, 500);
        $memo = 'Test Item';
        $type = 'stats';

        /** @var AffiliateInvoiceItem $affInvoiceItem */
        $affInvoiceItem = $this->hoClient->get(AffiliateInvoiceItem::class);
        $affInvoiceItem->invoice_id = $billId;
        $affInvoiceItem->offer_id = 8;
        $affInvoiceItem->memo = $memo;
        $affInvoiceItem->actions = $rand;
        $affInvoiceItem->amount = $rand;
        $affInvoiceItem->type = $type;
        $affInvoiceItem->payout_type = 'cpa_flat';
        $affInvoiceItem->create();

        /** @var AffiliateInvoiceItem $affInvoiceItemCheck */
        $affInvoiceItemCheck = $this->hoClient->get(AffiliateInvoice::class, $billId);

        $items = $affInvoiceItemCheck->getAffiliateInvoiceItem()->data();

        $itemKey = array_search((string)$affInvoiceItem->id[0], array_column($items, 'id'), true);
        isNotSame(false, $itemKey);
        isSame((string)$rand, $items[$itemKey]['actions']);
        isSame($memo, $items[$itemKey]['memo']);
        isSame($type, $items[$itemKey]['type']);
    }

    public function testCanDeleteInvoiceItem()
    {
        // get
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, 56);
        $items = $bill->getAffiliateInvoiceItem()->data();

        // find first one and delete it
        /** @var AffiliateInvoiceItem $billItem */
        $billItem = $this->hoClient->get(AffiliateInvoiceItem::class);
        $billItem->delete($items[0]['id']);

        // get bill items again
        /** @var AffiliateInvoice $billCheck */
        $billCheck = $this->hoClient->get(AffiliateInvoice::class, 56);
        $itemsCheck = $billCheck->getAffiliateInvoiceItem()->data();

        // check item is not among them
        $itemKey = array_search((string)$items[0]['id'], array_column($itemsCheck, 'id'), true);
        isSame(false, $itemKey);
    }
}
