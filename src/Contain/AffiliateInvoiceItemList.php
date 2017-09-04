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

namespace Unilead\HasOffers\Contain;

use Unilead\HasOffers\Entity\AbstractEntity;
use Unilead\HasOffers\Entity\AffiliateInvoice;

/**
 * Class AffiliateInvoiceItemList
 *
 * @package Unilead\HasOffers
 */
class AffiliateInvoiceItemList extends AbstractContain
{
    /**
     * @var AffiliateInvoice
     */
    protected $parentEntity;

    /**
     * @var string
     */
    protected $target = 'AffiliateInvoiceItem';

    /**
     * @var AffiliateInvoiceItem[]
     */
    protected $items = [];

    /**
     * @inheritdoc
     */
    public function __construct(array $data, AbstractEntity $parentEntity)
    {
        parent::__construct($data, $parentEntity);

        foreach ($data as $item) {
            $invoiceItem = new AffiliateInvoiceItem($item, $parentEntity);
            $this->items[] = $invoiceItem;
        }

        $this->bindData($data);
        $this->origData = $data;
    }

    public function findAll()
    {
        return $this->items;
    }

    public function findById($itemId)
    {
        $searchId = (int)$itemId;
        foreach ($this->items as $item) {
            if ($searchId === (int)$item->id) {
                return $item;
            }
        }

        return false;
    }

    public function addItem(array $data = [])
    {
        $invoiceItem = new AffiliateInvoiceItem($data, $this->parentEntity);
        $this->items[] = $invoiceItem;

        return $invoiceItem;
    }

    public function reload()
    {
        $this->parentEntity && $this->parentEntity->reload();
    }
}
