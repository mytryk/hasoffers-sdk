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

use JBZoo\Utils\Filter;
use Unilead\HasOffers\Contain\Country;
use Unilead\HasOffers\Contain\Goal;
use Unilead\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class Offer
 *
 * @property string advertiser_id                      ID of Advertiser object associated to offer (if any)
 * @property string allow_direct_links                 "Direct Links" setting in Offer Tracking Settings
 * @property string allow_multiple_conversions         "Multiple Conversions" setting in Offer Tracking Settings.
 * @property string allow_website_links                "Deep Links" setting in Offer Tracking Settings
 * @property string approve_conversions                Approve Conversions setting in Offer Tracking Settings.
 *                                                      A null value is treated as false.
 * @property string click_macro_url                    "Click Macro URL" setting in Offer Tracking Settings.
 *                                                      Applicable if disable_click_macro is set to false.
 * @property string conversion_cap                     "Daily Conversions" cap setting in Offer Settings.
 *                                                      A value of 0 means there is no general daily
 *                                                      conversion cap for offer.
 * @property string conversion_ratio_threshold         This parameter has been deprecated.
 * @property string converted_offer_id                 ID of Offer object for "Secondary Offer" setting
 *                                                      in Offer Tracking Settings. Applicable only
 *                                                      if converted_offer_type is set to "network".
 * @property string converted_offer_type               Core "Secondary Offer" setting in Offer Tracking Settings.
 *                                                      Applicable if "Redirect Offers" network-wide offer setting
 *                                                      is enabled and "SEO Friendly Links" network-wide
 *                                                      offer setting is disabled.
 * @property string converted_offer_url                Custom URL value for "Secondary Offer" setting in
 *                                                      Offer Tracking Settings. Applicable only if converted
 *                                                      offer_type is set to "url".
 * @property string create_date_utc                    Date the offer was created. This parameter is non-writable
 * @property string currency                           "Offer Currency" value in Offer Payout Settings, corresponding
 *                                                      to three-character code as listed in Using Multiple Currencies.
 *                                                      If set to null, offer uses "Currency" network-wide application
 *                                                      setting. Returns error if attempting to set field to any other
 *                                                      code. Available only if "Multiple Currencies" network-wide
 *                                                      application setting is enabled.
 * @property string customer_list_id                   ID of CustomerList object associated with offer (if any)
 * @property string default_goal_name                  Name of default goal (Goal.name). Applicable only if has_
 *                                                      goals_enabled is set to true.
 * @property string default_payout                     Flat payout amount for offer. Applicable only if payout_type
 *                                                      is or includes a flat-amount type: "cpa_flat",
 *                                                      "cpa_both", "cpc", or "cpm".
 * @property string description                        Offer's description text/HTML
 * @property string disable_click_macro                "Click Macro" setting in Offer Tracking Settings.
 *                                                      Is true if setting is set to "Disabled", false if set
 *                                                      to "Enabled". Applicable if "Global Click Macro" network-wide
 *                                                      tracking setting is enabled.
 * @property string display_advertiser                 "Display Advertiser" setting in Offer Settings Applicable
 *                                                      if "Display Advertiser" network-wide offer setting is enabled.
 * @property string dne_download_url                   Contents of Download URL entry in suppression list associated
 *                                                      to offer, as referred to in dne_list_id (if any). Cannot write
 *                                                      to this field. Use DneList controller to manipulate related
 *                                                      DneList object. This parameter is non-writable
 * @property string dne_list_id                        ID of DneList object associated to offer (if any).
 *                                                      Applicable only if show_mail_list is set to true.
 * @property string dne_third_party_list               Flag indicating if the offer uses a third-party DNE list.
 *                                                      This parameter is non-writable
 * @property string dne_unsubscribe_url                "Contents of Unsubscrube URL" entry in suppression list
 *                                                      associated to offer, as referred to in dne_list_id (if any).
 *                                                      Cannot write to this field. Use DneList controller to
 *                                                      manipulate related DneList object.
 *                                                      This parameter is non-writable
 * @property string email_instructions                 "Email Instructions" setting in Offer Settings.
 *                                                      Must be set to true for related fields to apply.
 * @property string email_instructions_from            "Contents of Approved From Lines" entry in offer's Suppression
 *                                                      Lists settings. Use line breaks to separate multiple entries.
 *                                                      Applicable if the "email_instructions" field is set to true.
 * @property string email_instructions_subject         "Contents of Approved Subject Lines" entry in offer's
 *                                                      Suppression Lists settings. Use line breaks to separate
 *                                                      multiple entries. Applicable if the "email_instructions"
 *                                                      field is set to true.
 * @property string enable_offer_whitelist             "Offer Whitelist" setting in Offer Tracking Settings
 * @property string enforce_encrypt_tracking_pixels    "Encrypted Conversion Tracking" setting in Offer Tracking
 *                                                      Settings. Applicable if "Encrypt Conversion URLs" network-wide
 *                                                      tracking setting is enabled.
 * @property string enforce_geo_targeting              "Enforce Geo-Targeting" setting in Offer Targeting.
 *                                                      Must be set to true for related fields to apply.
 * @property string enforce_secure_tracking_link       Enforce SSL by generating all affiliate tracking links and
 *                                                      impression pixels with https instead of http
 * @property string expiration_date                    Offer's expiration date
 * @property string featured                           Date offer was selected as a featured offer (available at the
 *                                                      network Snapshot page). If this is set to null or
 *                                                      "0000-00-00 00:00:00", offer is not selected as a featured offer
 * @property string has_goals_enabled                  "Multiple Conversion Goals" setting in Offer Payout.
 *                                                      Must be set to true for related fields to apply.
 * @property string hostname_id                        ID of Hostname object associated to offer (if any),
 *                                                      for use with custom tracking domains.
 * @property string id                                 This object's ID, automatically generated upon creation.
 *                                                      This parameter is non-writable
 * @property string is_expired                         Flag indicating if offer has expired—if the current date
 *                                                      is past the value in expiration_date.
 *                                                      This parameter is non-writable
 * @property string is_private                         "Private" setting in Offer Settings
 * @property string is_seo_friendly_301                "SEO-Friendly Links" setting in Offer Settings Applicable
 *                                                      if "SEO-Friendly Links" network-wide offer setting is enabled.
 * @property string is_subscription                    Subscription" setting in Offer Tracking Settings
 * @property string lifetime_conversion_cap            "Lifetime Conversions" cap setting in Offer Settings.
 *                                                      A value of 0 means there is no general lifetime
 *                                                      conversion cap for offer.
 * @property string lifetime_payout_cap                "Lifetime Payout" cap setting in Offer Settings.
 *                                                      A value of 0 means there is no general lifetime
 *                                                      payout cap for offer.
 * @property string lifetime_revenue_cap               "Lifetime Reveune" cap setting in Offer Settings.
 *                                                      A value of 0 means there is no general lifetime
 *                                                      payout cap for offer.
 * @property string max_payout                         Flat revenue amount for offer. Applicable only if revenue_type
 *                                                      is or includes a flat-amount type: "cpa_flat", "cpa_both",
 *                                                      "cpc", or "cpm". Note: Parameter name is a holdover.
 *                                                      This does refer to offer's revenue values.
 * @property string max_percent_payout                 Percentage of sale revenue for offer. Applicable only if
 *                                                      revenue_type is or includes a percentage type: "cpa_percentage"
 *                                                      or "cpa_both". Note: Parameter name is a holdover.
 *                                                      This does refer to offer's revenue values.
 * @property string modified                           Timestamp of most recent change to object.
 *                                                      This parameter is non-writable
 * @property string monthly_conversion_cap             "Monthly Conversions" cap setting in Offer Settings.
 *                                                      A value of 0 means there is no general monthly
 *                                                      conversion cap for offer.
 * @property string monthly_payout_cap                 "Monthly Payout" cap setting in Offer Settings.
 *                                                      A value of 0 means there is no general monthly payout cap
 *                                                      for offer.
 * @property string monthly_revenue_cap                "Monthly Revenue" cap setting in Offer Settings.
 *                                                      A value of 0 means there is no general monthly revenue cap
 *                                                      for offer.
 * @property string name                               Offer's display name
 * @property string note                               "Notes" field in Offer Settings. This parameter is exposed
 *                                                      to advertiser and network users only, not to affiliate users.
 * @property string offer_url                          Default offer URL/landing page offer redirects traffic to.
 *                                                      See Passing Values to Offer URLs for details on optional
 *                                                      variables and macros.
 * @property string payout_cap                         "Daily Payout" cap setting in Offer Settings. A value of 0
 *                                                      means there is no general daily payout cap for offer.
 * @property string payout_type                        Offer's payout type, as described in Offer Payouts. Values of
 *                                                      "cpa_flat", "cpm", and "cpc" indicate a flat payout amount,
 *                                                      which is specified in the default_payout field. Value of
 *                                                      "cpa_percentage" indicates payout is a percentage of sale,
 *                                                      which is specified in the percent_payout field. Value of
 *                                                      "cpa_both" indicates both a flat payout amount and a
 *                                                      percentage of sale payout.
 * @property string percent_payout                     Percentage of sale payout for offer. Applicable only if
 *                                                      payout_type is or includes a percentage type: "cpa_percentage"
 *                                                      or "cpa_both".
 * @property string preview_url                        URL used to preview page offer redirects to.
 * @property string protocol                           Conversion tracking protocol to use for offer.
 * @property string rating                             Offer's rating as displayed to affiliate and network users,
 *                                                      ranked from 1 to 5. This field is active if the network has
 *                                                      the Offer Ratings setting enabled.
 *                                                      This parameter is non-writable
 * @property string redirect_offer_id                  ID of Offer object for "Redirect Offer" setting in
 *                                                      Offer Tracking Settings.
 * @property string ref_id                             "Reference ID" setting in Offer Details
 * @property string require_approval                   Boolean    "Require Approval" setting in Offer Settings
 * @property string require_terms_and_conditions       "Terms and Conditions" setting in Offer Settings. If set to
 *                                                      "enabled," terms_and_conditions parameter should contain data.
 *                                                      Important: Use "enabled" and "disabled" for all inputs,
 *                                                      including for data objects and filter options. However, values
 *                                                      the API returns are "0" for disabled and "1" for enabled.
 * @property string revenue_cap                        "Daily Revenue" cap setting in Offer Settings. A value of 0
 *                                                      means there is no general daily revenue cap for offer.
 * @property string revenue_type                       Offer's revenue type, as described in Offer Payouts. Values of
 *                                                      "cpa_flat", "cpm", and "cpc" indicate a flat revenue amount,
 *                                                      which is specified in the max_payout field. Value of
 *                                                      "cpa_percentage" indicates revenue is a percentage of sale,
 *                                                      which is specified in the max_percent_payout field. Value of
 *                                                      "cpa_both" indicates both a flat revenue amount and a
 *                                                      percentage of sale revenue. Note: Values are same as in payout
 *                                                      type for unity, rather than using "rpa_flat" etc.
 * @property string session_hours                      "Click Session Lifespan" setting in Offer Tracking Settings,
 *                                                      in hours.
 * @property string session_impression_hours           "Impression Session Lifespan" setting in Offer Tracking Settings
 *                                                      in hours. Applicable only if set_session_on_impression
 *                                                      is set to true.
 * @property string set_session_on_impression          "Start Session Tracking" setting in Offer Tracking Settings;
 *                                                      true is selection is for impressions, false if for clicks.
 *                                                      Applicable only if protocol is set to a pixel-based value,
 *                                                      otherwise defaults to false.
 * @property string show_custom_variables              "Custom Variables" setting in Offer Tracking Settings.
 * @property string show_mail_list                     "Suppression List" setting in Offer Settings.
 * @property string status                             Offer's status
 * @property string subscription_duration              "Subscription Duration" setting in Offer Tracking Settings,
 *                                                      in seconds. A value of 0 means the duration is indefinite.
 *                                                      Applicable only if is_subscription is set to true.
 * @property string subscription_frequency             "Subscription Frequency" setting in Offer Tracking Settings.
 *                                                      Applicable only if is_subscription is set to true.
 * @property string target_browsers                    Integer This parameter has been deprecated.
 * @property string terms_and_conditions               Offer's terms and conditions text/HTML as shown in Offer
 *                                                      Settings. Should contain non-empty value if require_terms
 *                                                      and_conditions is true.
 * @property string tiered_payout                      Relates to "Payout Method" setting in Offer Payout Settings.
 *                                                      Is true if setting is set to "Tiered", false otherwise.
 *                                                      Cannot be set to true if use_payout_groups is also true.
 * @property string tiered_revenue                     Relates to "Revenue Method" setting in Offer Payout Settings.
 *                                                      Is true if setting is set to "Tiered", false otherwise.
 *                                                      Cannot be set to true if use_revenue_groups is also true.
 * @property string use_payout_groups                  Relates to "Payout Method" setting in Offer Payout Settings.
 *                                                      Is true if setting is set to "Groups", false otherwise.
 *                                                      Cannot be set to true if tiered_payout is also true.
 * @property string use_revenue_groups                 Relates to "Revenue Method" setting in Offer Payout Settings.
 *                                                      Is true if setting is set to "Groups", false otherwise.
 *                                                      Cannot be set to true if tiered_revenue is also true.
 * @property string use_target_rules                   "Advanced Targeting" setting in Offer Targeting.
 *                                                      Set to true if "Show the offer to targeted devices" is selected.
 * @property string website_links_copy_static_params   "Copy Static Parameters to Deep Links" setting in Offer
 *                                                      Tracking Settings. Applicable only if allow_website_links
 *                                                      is true.
 *
 * @method Goal getGoal()
 * @method Country getCountry()
 *
 * @package Unilead\HasOffers\Entity
 */
class Offer extends AbstractEntity
{
    use Deleted;

    const STATUS_ACTIVE  = 'active';
    const STATUS_PAUSED  = 'paused';
    const STATUS_PENDING = 'pending';
    const STATUS_EXPIRED = 'expired';
    const STATUS_DELETED = 'deleted';

    const DEFAULT_GOAL_NAME = 'Install and Open';

    static private $trackingUrls = [
        'Adbazaar'       => ['adbazaar.net/'],
        'AppsFlyer'      => ['app.appsflyer.com/'],
        'Adsimilis'      => ['adsimilis.com/'],
        'Apsalar'        => ['apsalar.com/'],
        'App4u'          => ['app4u.today/'],
        'AppMetrica'     => ['appmetrica.yandex.com/', 'appmetrika.yandex.ru'],
        'Apple'          => ['apple.com/'],
        'Ad-X'           => ['ad-x.co.uk/'],
        'Actionpay'      => ['actionpay.ru/'],
        'AppMetr'        => ['appmetr.com/', 'pixapi.net'],
        'ad2games'       => ['ad2games.com/'],
        'AppLift'        => ['applift.com/'],
        'Adjust'         => ['adjust.io/', 'adjust.com/'],
        'Crobo'          => ['affiliates.de/', 'crobo.com'],
        'Clickky'        => ['clickky.biz/'],
        'Facebook'       => ['facebook.com/'],
        'GMobi'          => ['generalmobi.com/'],
        'Google'         => ['google.com/', 'google.ru'],
        'Glispa'         => ['glispa.com/'],
        'Plexop'         => ['serving.plexop.net/'],
        'Mail.ru'        => ['mail.ru/'],
        'MarsAds'        => ['marsads.com/'],
        'MADNETex'       => ['madnet.ru/'],
        'Mobbnet'        => ['mobbnet.com/'],
        'Mobilda'        => ['mobilda.com/'],
        'Mobicolor'      => ['mobicolor.com/'],
        'Raftica'        => ['raftika.com/'],
        'RebornGame'     => ['reborngame.ru/'],
        'IconPeak'       => ['iconpeak.com/'],
        'Kochava'        => ['kochava.com/'],
        'Taptica'        => ['tracking.taptica.com/'],
        'InstallTracker' => ['installtracker.com/'],
        'Unilead'        => ['unileadnetwork.com', 'unilead.ru/'],
        'Wooga'          => ['woogatrack.com/'],
        'Wakeapp'        => ['paymaks.com/'],
    ];

    /**
     * @var string
     */
    protected $target = 'Offer';

    /**
     * @var array
     */
    protected $methods = [
        'get'    => 'findById',
        'create' => 'create',
        'update' => 'update',
    ];

    /**
     * @var array
     */
    protected $contain = [
        'Goal'    => Goal::class,
        'Country' => Country::class,
    ];

    /**
     * @return float
     */
    public function getMonthlyRevenueCap()
    {
        return Filter::float($this->monthly_revenue_cap) > 0
            ? Filter::float($this->monthly_revenue_cap)
            : $this->getBudget();
    }

    /**
     * Find Offer Budget (MonthlyRevenueCap).
     *
     * @return float
     */
    public function getBudget()
    {
        return (Filter::float($this->monthly_conversion_cap) > 0 && Filter::float($this->max_payout) > 0)
            ? Filter::float($this->monthly_conversion_cap) * Filter::float($this->max_payout)
            : 0.0;
    }

    /**
     * @return float
     */
    public function getMonthlyCapAmount()
    {
        return (Filter::float($this->monthly_conversion_cap) > 0)
            ? Filter::float($this->monthly_conversion_cap)
            : $this->getMonthlyConversionsCap();
    }

    /**
     * @return float
     */
    public function getMonthlyConversionsCap()
    {
        return (Filter::float($this->monthly_revenue_cap) > 0 && Filter::float($this->max_payout) > 0)
            ? (Filter::float($this->monthly_revenue_cap) / Filter::float($this->max_payout))
            : 0.0;
    }

    /**
     * Function get all TargetRules for specified Offer.
     *
     * @return array|null
     */
    public function getRuleTargeting()
    {
        $targetData = $this->hoClient->apiRequest([
            'Target'   => 'OfferTargeting',
            'Method'   => 'getRuleTargetingForOffer',
            'offer_id' => $this->id,
        ])->getArrayCopy();

        if (!empty($targetData)) {
            foreach ((array)$targetData as $targeting) {
                $map = [
                    'iPad'         => 'iOS',
                    'iPhone'       => 'iOS',
                    'iOS'          => 'iOS',
                    'Android'      => 'Android',
                    'WindowsPhone' => 'Windows',
                    '_default'     => '',
                ];

                //TODO: Doesn't work on some offers. Need to check why (Nick)
                $platform = $map[$targeting['rule']['name']] ?? $map['_default'];

                $return[] = [
                    'OfferId'     => $this->id,
                    'Name'        => $targeting['rule']['name'],
                    'Description' => $targeting['rule']['description'],
                    'Category'    => $targeting['rule']['category'],
                    'Platform'    => $platform,
                ];
            }

            if (!empty($return)) {
                return $return;
            }
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getTrackingSystem()
    {
        return isset($this->offer_url) ? $this->parseTrackingSystem() : null;
    }

    /**
     * Parse Offer url to get offer Tracking System.
     *
     * @return string
     */
    private function parseTrackingSystem()
    {
        /** @var array $urlList */
        foreach (self::$trackingUrls as $catalogName => $urlList) {
            foreach ($urlList as $catalogUrl) {
                if (strpos($this->offer_url, $catalogUrl) !== false) {
                    return $catalogName;
                }
            }
        }

        return 'Other';
    }

    /**
     * @return string
     */
    public function getDefaultGoal()
    {
        return $this->default_goal_name ?: self::DEFAULT_GOAL_NAME;
    }

    /**
     * @return string
     */
    public function getCountriesCodes()
    {
        $countries = $this->getCountry()->data()->getArrayCopy();

        $countrieCodes = array_reduce($countries, function ($acc, $item) {
            $acc[] = $item['code'];
            return $acc;
        }, []);

        sort($countrieCodes);

        return implode(';', $countrieCodes);
    }
}
