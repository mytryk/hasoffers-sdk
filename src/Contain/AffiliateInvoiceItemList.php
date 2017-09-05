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
 * Class AffiliateInvoiceItemList
 *
 * @package Unilead\HasOffers
 */
class AffiliateInvoiceItemList extends AbstractContain
{
    use DataList;

    /**
     * @var string
     */
    protected $target = 'AffiliateInvoiceItem';

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
