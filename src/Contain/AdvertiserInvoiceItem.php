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

use JBZoo\Data\Data;
use Unilead\HasOffers\Traits\Data as DataTrait;
use Unilead\HasOffers\Entity\AdvertiserInvoice;

/**
 * Class AdvertiserInvoiceItem
 * @package Unilead\HasOffers
 */
class AdvertiserInvoiceItem
{
    use DataTrait;

    /**
     * @var AdvertiserInvoice
     */
    protected $invoice;

    /**
     * @var Data
     */
    protected $items;

    /**
     * PaymentMethod constructor.
     *
     * @param array             $data
     * @param AdvertiserInvoice $advertiserInvoice
     */
    public function __construct(array $data, AdvertiserInvoice $advertiserInvoice)
    {
        $this->invoice = $advertiserInvoice;
        $this->items = new Data($data);
    }

    /**
     * @return Data
     */
    public function getRawData()
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function reload()
    {
        $this->bindData($this->getRawData()->getArrayCopy());
    }
}
