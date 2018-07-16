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

use Item8\HasOffers\Entity\Affiliate;

/**
 * Class AffiliateMeta
 *
 * @property string affiliate_id    Integer             The ID of the Affiliate whose payment details are specified
 * @property string ssn_tax         Nullable String     The VAT / Tax ID for the Affiliate. This field is present only
 *                                                      if the Network has VAT taxing enabled (dictated by the
 *                                                      Preference named "enable_affiliate_vat").
 *
 * @package Item8\HasOffers
 */
class AffiliateMeta extends AbstractClientMeta
{
    /**
     * @var Affiliate
     */
    protected $parentEntity;

    /**
     * @var array
     */
    protected $excludedKeys = [
        'affiliate_id'
    ];

    /**
     * @var Affiliate
     */
    protected $target = 'AffiliateMeta';

    protected $billingName = 'AffiliateBilling';
}
