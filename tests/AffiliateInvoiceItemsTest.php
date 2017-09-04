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

        $items = $affiliateInvoice->getItemsResultSet()->findAll();

        foreach ($items as $item) {
            is($someId, $item->invoice_id);
        }
    }

    public function testCanCreateInvoiceItem()
    {
        $billId = 56;
        $rand = random_int(1, 500);
        $memo = 'Test Item';
        $type = 'stats';

        /** @var AffiliateInvoice $affInvoice */
        $affInvoice = $this->hoClient->get(AffiliateInvoice::class, $billId);
        $affInvoiceItemsResultSet = $affInvoice->getItemsResultSet();
        $affInvoiceItem = $affInvoiceItemsResultSet->addItem();
        $affInvoiceItem->invoice_id = $billId;
        $affInvoiceItem->offer_id = 8;
        $affInvoiceItem->memo = $memo;
        $affInvoiceItem->actions = $rand;
        $affInvoiceItem->amount = $rand;
        $affInvoiceItem->type = $type;
        $affInvoiceItem->payout_type = 'cpa_flat';
        $affInvoiceItem->save();

        $item = $affInvoiceItemsResultSet->findById($affInvoiceItem->id);

        isNotSame(false, $item);
        isSame((string)$rand, (string)$item->actions);
        isSame($memo, $item->memo);
        isSame($type, $item->type);
    }

    public function testCanDeleteInvoiceItem()
    {
        // get
        /** @var AffiliateInvoice $bill */
        $bill = $this->hoClient->get(AffiliateInvoice::class, 56);
        $items = $bill->getAffiliateInvoiceItem()->data()->getArrayCopy();

        // find first one and delete it
        /** @var AffiliateInvoiceItem $billItem */
        $billItem = $this->hoClient->get(AffiliateInvoiceItem::class);
        $billItem->delete($items[0]['id']);

        // get bill items again
        /** @var AffiliateInvoice $billCheck */
        $billCheck = $this->hoClient->get(AffiliateInvoice::class, 56);
        $itemsCheck = $billCheck->getAffiliateInvoiceItem()->data()->getArrayCopy();

        // check item is not among them
        $itemKey = array_search((string)$items[0]['id'], array_column($itemsCheck, 'id'), true);
        isSame(false, $itemKey);
    }
}
