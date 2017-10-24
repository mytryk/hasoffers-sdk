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

use Unilead\HasOffers\Traits\ArrayTrait;

/**
 * Class Conversion
 *
 * @property string ad_campaign_creative_id     The ID of the Ad Campaign Creative; cannot be updated
 * @property string ad_campaign_id              The ID of the Ad Campaign; cannot be updated
 * @property string ad_id                       ID of Transaction object created by the Ad Server; cannot be updated
 * @property string advertiser_id               The ID of the Advertiser for the Offer; cannot be updated
 * @property string advertiser_info             Advertiser sub passed in by the Advertiser when the
 *                                              conversion pixel / URL was called; cannot be updated
 * @property string advertiser_manager_id       The ID of the Employee who is the account manager for the Advertiser;
 *                                              cannot be updated
 * @property string affiliate_id                The ID of the Affiliate that generated this Conversion;
 *                                              cannot be updated
 * @property string affiliate_info1             Affiliate sub 1 passed in by the Affiliate when the
 *                                              session was started; cannot be updated
 * @property string affiliate_info2             Affiliate sub 2 passed in by the Affiliate when the
 *                                              session was started; cannot be updated
 * @property string affiliate_info3             Affiliate sub 3 passed in by the Affiliate when the
 *                                              session was started; cannot be updated
 * @property string affiliate_info4             Affiliate sub 4 passed in by the Affiliate when the
 *                                              session was started; cannot be updated
 * @property string affiliate_info5             Affiliate sub 5 passed in by the Affiliate when the
 *                                              session was started; cannot be updated
 * @property string affiliate_manager_id        The ID of the Employee who is the account manager for the
 *                                              Affiliate; cannot be updated
 * @property string browser_id                  The ID of the Browser for the user when the session was
 *                                              started; cannot be updated
 * @property string country_code                The country code for the user when the session was
 *                                              started; cannot be updated
 * @property string creative_url_id             The ID of the Offer Url; cannot be updated
 * @property string currency                    The Currency for the Conversion; cannot be updated
 * @property string customer_id                 ID of Customer object; cannot be updated
 * @property string datetime                    The time of the conversion; cannot be updated. Required
 *                                              format: YYYY-MM-DD hh:mm:ss
 * @property string goal_id                     The ID of the Goal this Conversion is for. Only applicable if
 *                                              the "enable_offer_goals" Preference is enabled; cannot be updated.
 * @property string id                          ID of unique, auto-generated object for this Conversion. This
 *                                              parameter is non-writable
 * @property string internal_ad_id              ID of Internal object created by Ad Server for
 *                                              internal tracking purposes; cannot be updated. non-writable
 * @property string ip                          IP address used to create the Conversion
 * @property string is_adjustment               Whether the Conversion was created as an adjustment;
 *                                              cannot be updated
 * @property string offer_file_id               The ID of the Offer File; cannot be updated
 * @property string offer_id                    The ID of the Offer this Conversion is for; cannot be updated
 * @property string payout                      The amount to be paid to the Affiliate for this Conversion
 * @property string payout_type                 The "payout_type" of the Offer (or Goal, if applicable);
 *                                              cannot be updated
 * @property string pixel_refer                 Referral URL for the conversion pixel
 * @property string refer                       Referral URL where session was started
 * @property string revenue                     The amount of revenue generated by this Conversion, owed to
 *                                              the Network by the Advertiser
 * @property string revenue_type                The "revenue_type" of the Offer (or Goal, if applicable);
 *                                              cannot be updated
 * @property string sale_amount                 The sale amount generated for the Advertiser by this Conversion
 * @property string session_datetime            The time the session was started
 * @property string session_ip                  The IP address used to start the session
 * @property string source                      The affiliate source passed in by the Affiliate when
 *                                              the session was started; cannot be updated
 * @property string status                      Status of the Conversion
 * @property string status_code                 The code providing more granular status details.
 *                                              For more information, see:
 *                                              http://support.hasoffers.com/hc/en-us/articles/203507933-Conversion-Status-Codes
 * @property string user_agent                  User agent for user when session was started
 *
 * @package Unilead\HasOffers\Entity
 */
class Conversion
{
    const STATUS_APPROVED = 'approved';
    const STATUS_PENDING  = 'pending';
    const STATUS_REJECTED = 'rejected';

    // ATTENTION! See dump of findAll results to set this indexes
    const ID            = 0;
    const AFFILIATE_ID  = 1;
    const ADVERTISER_ID = 2;
    const OFFER_ID      = 3;
    const GOAL_ID       = 4;
    const DATETIME      = 5;
    const PAYOUT        = 6;
    const REVENUE       = 7;
    const STATUS        = 8;
    const IS_ADJUSTMENT = 9;
    const PAYOUT_TYPE   = 10;
    const REVENUE_TYPE  = 11;
    const CURRENCY      = 12;

    public static $fields = [
        'id',
        'affiliate_id',
        'advertiser_id',
        'offer_id',
        'goal_id',
        'datetime',
        'payout',
        'revenue',
        'status',
        'is_adjustment',
        'payout_type',
        'revenue_type',
        'currency',
    ];
}
