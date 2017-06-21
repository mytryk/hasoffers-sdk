<?php
/**
 * Unilead | BM
 *
 * This file is part of the Unilead Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     BM
 * @license     Proprietary
 * @copyright   Copyright (C) Unilead Network, All rights reserved.
 * @link        https://www.unileadnetwork.com
 */

namespace Unilead\HasOffers\Entity;

use Unilead\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class AffiliateInvoice
 *
 * @property string $advertiser_id If the Preference "enable_invoices_by_advertiser" is enabled, this can contain the
 *                                 Advertiser ID for invoices generated by Advertiser.
 *                                 May be NULL or set to "0" otherwise.
 * @property string $affiliate_id  The ID of the Affiliate the invoice is for
 * @property string $actions       The sum of all counts/clicks/conversions found on the invoice's items
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
 * @property string $memo          Memo the Network attaches to the invoice
 * @property string $notes         Internal notes the Network can attach to this invoice
 * @property string $receipt_id    The ID of the Affiliate Receipt that was used to pay the invoice. Present if the
 *                                 invoice has been paid out.
 * @property string $start_date    Start of date range; use "YYYY-MM-DD" format
 * @property string $status        The status of the invoice
 *
 * @package Unilead\HasOffers\Entity
 */
class AffiliateInvoice extends AbstractEntity
{
    use Deleted;

    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_INCOMPLETE = 'incomplete';

    /**
     * @var string
     */
    protected $target = 'AffiliateBilling';

    /**
     * @var string
     */
    protected $targetAlias = 'AffiliateInvoice';

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
    protected $contain = [];
}
