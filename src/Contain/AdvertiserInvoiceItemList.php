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
use Unilead\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoiceItemList
 *
 * @package Unilead\HasOffers
 */
class AdvertiserInvoiceItemList extends AbstractContain
{
    /**
     * @var AdvertiserInvoice
     */
    protected $parentEntity;

    /**
     * @var string
     */
    protected $target = 'AdvertiserInvoiceItem';

    /**
     * @var AdvertiserInvoiceItem[]
     */
    protected $items = [];

    /**
     * @inheritdoc
     */
    public function __construct(array $data, AbstractEntity $parentEntity)
    {
        parent::__construct($data, $parentEntity);

        foreach ($data as $item) {
            $invoiceItem = new AdvertiserInvoiceItem($item, $parentEntity);
            $this->items[] = $invoiceItem;
        }

        $this->bindData($data);
        $this->origData = $data;
    }

    public function getList()
    {
        return $this->items;
    }

    public function getItemById($id)
    {
        $searchId = (int)$id;
        foreach ($this->items as $item) {
            if ($searchId === (int)$item->id) {
                return $item;
            }
        }

        return false;
    }
}
