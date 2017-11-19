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

namespace Item8\HasOffers\Entity;

/**
 * Class OfferPixel
 *
 * @property string affiliate_id    The ID of the Affiliate to whom this Offer Pixel belongs. If "0" this applies to
 *                                  all Affiliates.
 * @property string code            Valid URL if type parameter is set to "url", otherwise HTML code. Some variables
 *                                  can be used, which will be dynamically replaced. For more information, see:
 *                                  http://support.hasoffers.com/hc/en-us/articles/202674498-Conversion-Pixels-URLs.
 * @property string goal_id         The Goal this Pixel is for. If NULL, the Pixel is for the Offer referenced in the
 *                                  "offer_id" field. This field should only be utilized if the "enable_offer_goals"
 *                                  Preference is enabled.
 * @property string id              ID of unique, auto-generated object for this Offer Pixel.
 *                                  This parameter is non-writable
 * @property string modified        The last time this Offer Pixel was modified
 * @property string offer_id        The Offer that this Pixel is for. If set to "0" this applies to all
 *                                  Offers and this will be used if Offer ID is not passed in the call.
 * @property string status          The status of the Offer Pixel
 * @property string type            The type of Pixel. Some offers only allow a subset of the listed types. Valid types
 *                                  for an Offer can be determined by calling the OfferPixel::getAllowedTypes API
 *                                  function.
 *
 * @package Item8\HasOffers\Entity
 */
class OfferPixel
{
    const STATUS_ACTIVE   = 'active';
    const STATUS_PENDING  = 'pending';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DELETED  = 'deleted';

    const TYPE_URL   = 'url';
    const TYPE_CODE  = 'code';
    const TYPE_IMAGE = 'image';

    // ATTENTION! See dump of findAll results to set this indexes
    const ID           = 0;
    const AFFILIATE_ID = 1;
    const OFFER_ID     = 2;
    const STATUS       = 3;
    const CODE         = 4;
    const TYPE         = 5;
    const MODIFIED     = 6;
    const GOAL_ID      = 7;
}
