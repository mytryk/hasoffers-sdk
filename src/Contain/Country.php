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

use Unilead\HasOffers\Entity\Offer;

/**
 * Class Country
 *
 * @property string code           String              The 2-character country code
 * @property string code3c         String              The 3-character country code
 * @property string id             Integer             A unique, auto-generated ID for the country
 * @property string is_active      Nullable Boolean    DEPRECATED. This field should be ignored.
 * @property string name           String              The country name
 * @property string paypal_code    Nullable String     DEPRECATED. This field should be ignored.
 * @property string regions        Nullable Array      A list of regions in the country. This field may not always be
 *                                                     present for all API calls.
 *
 * @package Unilead\HasOffers
 */
class Country extends AbstractContain
{
    /**
     * @var Offer
     */
    protected $parentEntity;

    /** @var string */
    protected $target = 'Country';
}
