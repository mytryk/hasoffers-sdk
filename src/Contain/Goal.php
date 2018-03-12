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

use Item8\HasOffers\Entity\AbstractEntity;
use Item8\HasOffers\Entity\Offer;

/**
 * Class Goal
 *
 * @property string advertiser_id                       The ID of the Advertiser for the Offer this Goal belongs to
 * @property string allow_multiple_conversions          Whether to allow multiple conversions for this Goal
 * @property string approve_conversions                 Whether conversions on this Goal require manual approval.
 *                                                      Applicable only if the "enable_conversion_approval" Preference
 *                                                      is enabled.
 * @property string default_payout                      The flat rate/amount paid for this Goal. The value in this
 *                                                      field is applicable only if "payout_type" is set to "cpa_flat"
 *                                                      or "cpa_both".
 * @property string description                         A description of this Goal
 * @property string display_advertiser                  Whether or not to display the Advertiser to users who otherwise
 *                                                      lack permission to view them in the application
 * @property string enforce_encrypt_tracking_pixels     Whether to enforce encrypted conversion tracking. Applicable
 *                                                      only if the "encrypt_tracking_pixels" Preference is enabled;
 *                                                      defaults on if the "encrypt_tracking_pixels" Preference is
 *                                                      enabled, else off.
 * @property string id                                  ID of unique, auto-generated object for this Goal This
 *                                                      parameter is non-writable
 * @property string is_end_point                        Enabling this setting will close the active session when this
 *                                                      Goal is converted. This prevents the user from further
 *                                                      converting on the Offer unless another active session is
 *                                                      started. Set this to enabled on the last Goal in the flow
 *                                                      process.
 * @property string is_private                          Whether this Goal should be hidden from Affiliates and used
 *                                                      only to track revenue
 * @property string max_payout                          The revenue received for this Goal
 * @property string max_percent_payout                  The percent revenue received for this Goal
 * @property string modified                            The last time this Goal was updated
 * @property string name                                The name of the Goal
 * @property string offer_id                            The ID of the Offer this Goal belongs to
 * @property string payout_type                         Specifies the method of calculating payout for this Goal.
 *                                                      "cpa_flat" indicates a flat amount will be paid, specified in
 *                                                      the "default_payout" field. "cpa_percentage" indicates that a
 *                                                      percentage of the sale will be paid, specified in the
 *                                                      "percent_payout" field. "cpa_both" indicates that both a flat
 *                                                      rate and a percentage of the sale will be paid.
 * @property string percent_payout                      The percent of sale paid for this Goal. For example, a value of
 *                                                      "25.00" would indicate a 25% payout. The value in this field is
 *                                                      applicable only if "payout_type" is set to "cpa_percentage" or
 *                                                      "cpa_both".
 * @property string protocol                            Conversion tracking method to be implemented for goal
 * @property string ref_id                              ID of A reference object such as an external product ID, to
 *                                                      associate with the Goal
 * @property string revenue_type                        Specifies the method of calculating revenue for this Goal.
 *                                                      "cpa_flat" indicates a flat amount will be revenue, specified
 *                                                      in the "default_payout" field.
 *                                                      "cpa_percentage" indicates that a percentage of the sale will
 *                                                      be revenue, specified in the
 *                                                      "percent_payout" field. "cpa_both" indicates that both a flat
 *                                                      rate and a percentage of the sale will be revenue.
 * @property string status                              The status of the Goal
 * @property string tiered_payout                       Whether to use Affiliate Tiers for payout calculation for
 *                                                      Affiliates for this Goal. Cannot be enabled at the same time as
 *                                                      "use_payout_groups". For more information, see:
 *                                                      http://support.hasoffers.com/hc/en-us/articles/202812086-Affiliate-Payout-Tiers.
 * @property string tiered_revenue                      Whether to use Affiliate Tiers for revenue calculation for
 *                                                      Affiliates for this Goal. Cannot be enabled at the same time as
 *                                                      "use_revenue_groups". For more information, see:
 *                                                      http://support.hasoffers.com/hc/en-us/articles/202812086-Affiliate-Payout-Tiers.
 * @property string use_payout_groups                   Whether to use Payout Groups to calculate revenue for
 *                                                      Affiliates for this Goal. Cannot be enabled at the same time as
 *                                                      "tiered_payout". For more information, see:
 *                                                      http://support.hasoffers.com/hc/en-us/articles/202305336-Payout-Revenue-Groups.
 * @property string use_revenue_groups                  Whether to use Revenue Groups to calculate revenue for
 *                                                      Affiliates for this Goal. Cannot be enabled at the same time as
 *                                                      "tiered_revenue". For more information, see:
 *                                                      http://support.hasoffers.com/hc/en-us/articles/202305336-Payout-Revenue-Groups.
 *
 * @package Item8\HasOffers
 */
class Goal extends AbstractContain
{
    /** @var string */
    protected $target = 'Goal';

    /**
     * @var Offer
     */
    protected $parentEntity;

    /**
     * @inheritdoc
     */
    public function __construct(array $data, AbstractEntity $parentEntity)
    {
        $this->parentEntity = $parentEntity;
        $this->hoClient = $this->parentEntity->getClient();
        $this->bindData($data);

        if (!$this->target) {
            throw new Exception('Target is no set for ' . static::class);
        }
    }
}
