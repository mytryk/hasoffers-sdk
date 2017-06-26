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
use Unilead\HasOffers\Entity\AffiliateInvoice;

/**
 * Class InvoiceItem
 * @package Unilead\HasOffers
 */
class InvoiceItem
{
    use DataTrait;

    /**
     * @var AffiliateInvoice
     */
    protected $invoice;

    /**
     * @var Data
     */
    protected $items;

    /**
     * PaymentMethod constructor.
     *
     * @param array            $data
     * @param AffiliateInvoice $affiliateInvoice
     */
    public function __construct(array $data, AffiliateInvoice $affiliateInvoice)
    {
        $this->invoice = $affiliateInvoice;
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
