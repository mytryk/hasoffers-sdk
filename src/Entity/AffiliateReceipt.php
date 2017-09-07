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

namespace Unilead\HasOffers\Entity;

use Unilead\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class AffiliateReceipt
 *
 * @property string $affiliate_id       The ID of the Affiliate to whom this receipt records payment
 * @property string $amount             The amount paid
 * @property string $currency           The 3-character currency code identifying the currency used for this payment
 * @property string $date               The date of the payment
 * @property string $datetime           The date this receipt was created (not necessarily the date of payment)
 * @property string $id                 ID of unique object for this Receipt
 * @property string $memo               A memo visible to the Affiliate
 * @property string $method             The method used to make the payment
 * @property string $notes              Internal notes
 * @property string $payment_details    A serialized form of the Affiliate's payment method details that
 *                                      were used to make this payment. This allows the payment details to be
 *                                      visible in the future even if the Affiliate changes
 *                                      their billing preferences.
 * @property string $payment_info       Same as the "payment_details" field but in unserialized (object) form
 * @property string $status             The status of this payment
 * @property string $token              A payment reference field without any predefined meaning.
 *                                      Empty string if no value is present
 * @property string $transaction_id     A payment reference field without any predefined meaning.
 *                                      Empty string if no value is present.
 *
 * @method AffiliateInvoice[]           getAffiliateInvoice()
 * @method Affiliate                    getAffiliate()
 *
 * @package Unilead\HasOffers\Entity
 */
class AffiliateReceipt extends AbstractEntity
{
    use Deleted;

    const STATUS_SUCCESS = 'success';
    const STATUS_DELETED = 'deleted';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED  = 'failed';

    const PAYMENT_METHOD_CHECK         = 'check';
    const PAYMENT_METHOD_DIRECTDEPOSIT = 'direct_deposit';
    const PAYMENT_METHOD_OTHER         = 'other';
    const PAYMENT_METHOD_PAYONEER      = 'payoneer';
    const PAYMENT_METHOD_PAYPAL        = 'paypal';
    const PAYMENT_METHOD_PAYQUICKER    = 'payquicker';
    const PAYMENT_METHOD_WIRE          = 'wire';

    /**
     * @var string
     */
    protected $target = 'AffiliateBilling';

    /**
     * @var string
     */
    protected $targetAlias = 'AffiliateReceipt';

    /**
     * @var array
     */
    protected $methods = [
        'get'    => 'findReceiptById',
        'create' => 'createReceipt',
        'update' => 'updateReceipt',
    ];
}
