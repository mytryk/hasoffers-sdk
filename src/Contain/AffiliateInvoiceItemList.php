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

namespace Item8\HasOffers\Contain;

use Item8\HasOffers\Entity\AbstractEntity;
use Item8\HasOffers\Traits\DataList;

/**
 * Class AffiliateInvoiceItemList
 *
 * @package Item8\HasOffers
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
            $this->addItem($item);
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
