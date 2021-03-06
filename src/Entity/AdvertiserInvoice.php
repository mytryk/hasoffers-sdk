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

namespace Item8\HasOffers\Entity;

use Item8\HasOffers\Contain\AdvertiserInvoiceItemList;
use Item8\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class AdvertiserInvoice
 *
 * @property string $advertiser_id The ID of the Advertiser the invoice is for
 * @property string $amount        The amount of the invoice (this is the sum of amounts for all this invoice's unpaid
 *                                 items)
 * @property string $conversions   DEPRECATED. Ignore the contents of this field.
 * @property string $currency      The 3-character currency code identifying the currency used for this invoice. If not
 *                                 specified, defaults to the Network currency defined by the "network_currency"
 *                                 Preference.
 * @property string $datetime      The time the invoice was created
 * @property string $end_date      End of date range; use "YYYY-MM-DD" format
 * @property string $id            ID of unique, auto-generated object for the Invoice
 * @property string $is_paid       Whether or not the invoice has been paid
 * @property string $is_sent       Whether or not the invoice has been sent to the Advertiser
 * @property string $memo          Memo the Network attaches to the invoice
 * @property string $notes         Internal notes the Network can attach to this invoice
 * @property string $start_date    Start of date range; use "YYYY-MM-DD" format
 * @property string $status        The status of the invoice
 *
 * @package Item8\HasOffers\Entity
 */
class AdvertiserInvoice extends AbstractEntity
{
    use Deleted;

    const STATUS_ACTIVE  = 'active';
    const STATUS_DELETED = 'deleted';

    /**
     * @var string
     */
    protected $target = 'AdvertiserBilling';

    /**
     * @var string
     */
    protected $targetAlias = 'AdvertiserInvoice';

    /**
     * @var array
     */
    protected $methods = [
        'get'    => 'findInvoiceById',
        'create' => 'createInvoice',
        'update' => 'updateInvoice',
    ];

    /**
     * @var array
     */
    protected $contain = [
        'AdvertiserInvoiceItem' => AdvertiserInvoiceItemList::class,
    ];

    /**
     * Just fix naming
     *
     * @return AdvertiserInvoiceItemList
     */
    public function getItemsList()
    {
        return $this->getAdvertiserInvoiceItem();
    }
}
