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
use Unilead\HasOffers\Traits\DataList;

/**
 * Class AdvertiserInvoiceItemList
 *
 * @package Unilead\HasOffers
 */
class AdvertiserInvoiceItemList extends AbstractContain
{
    use DataList;

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
            $this->addItem($item);
        }

        $this->bindData($data);
        $this->origData = $data;
    }

    public function addItem(array $data = [])
    {
        $invoiceItem = new AdvertiserInvoiceItem($data, $this->parentEntity);
        $this->items[] = $invoiceItem;

        return $invoiceItem;
    }

    public function reload()
    {
        $this->parentEntity && $this->parentEntity->reload();
    }
}
