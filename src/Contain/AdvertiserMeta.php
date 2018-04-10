<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     Proprietary
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers\Contain;

use Item8\HasOffers\Entity\Advertiser;

/**
 * Class AdvertiserMeta
 *
 * @property int    advertiser_id   Integer             The ID of the Advertiser
 * @property string default_vat_id  Nullable Integer    The ID of the default VAT Rate to apply to invoices
 * @property string ssn_tax         Nullable String     ID of SSN/VAT object/Fiscal Code or other tax ID
 *
 * @package Item8\HasOffers
 */
class AdvertiserMeta extends AbstractClientMeta
{
    public const DEFAULT_VAT_ID = '0';

    /**
     * @var Advertiser
     */
    protected $parentEntity;

    /**
     * @var Advertiser
     */
    protected $target = 'AdvertiserMeta';

    protected $billingName = 'AdvertiserBilling';
}
