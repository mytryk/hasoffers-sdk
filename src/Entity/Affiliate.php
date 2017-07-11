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

use Unilead\HasOffers\Contain\PaymentMethod;
use Unilead\HasOffers\Contain\AffiliateUser;
use Unilead\HasOffers\Traits\Blocked;
use Unilead\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class Affiliate
 *
 * @property string $account_manager_id             The ID of the Employee from the Network who is assigned as
 *                                                  the account manager for the Affiliate
 * @property string $address1                       The first line of the account's physical address
 * @property string $address2                       The second line of the account's physical address
 * @property string $affiliate_tier_id              The ID of the Affiliate Tier to which this Affiliate is assigned.
 *                                                  If no value is present, the Affiliate is in the default tier.
 * @property string $city                           The city of the account's physical address
 * @property string $company                        The company name of the Affiliate
 * @property string $country                        The country of the account's physical address
 * @property string $date_added                     The date this account was created
 * @property string $fax                            The fax number of the Affiliate
 * @property string $fraud_activity_alert_threshold The activity fraud threshold (percent out of 100) at
 *                                                  which point an alerts will be generated for this account
 *                                                  when its recent activity is flagged as potentially fraudulent.
 *                                                  Applicable only if the "affiliate_fraud_activity" Preference
 *                                                  is enabled. For more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202674578-Affiliate-Activity-Fraud.
 * @property string $fraud_activity_block_threshold The activity fraud threshold (percent out of 100) at which point
 *                                                  the account will be automatically blocked (status set to "blocked")
 *                                                  when its recent activity is flagged as potentially fraudulent.
 *                                                  Applicable only if the "affiliate_fraud_activity" Preference is
 *                                                  enabled. For more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202674578-Affiliate-Activity-Fraud.
 * @property string $fraud_activity_score           Activity fraud score for the Affiliate.
 *                                                  Applicable only if the "affiliate_fraud_activity" Preference
 *                                                  is enabled. This should not be manually updated/overridden
 *                                                  as the API will update this score automatically.
 *                                                  For more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202674578-Affiliate-Activity-Fraud.
 * @property string $fraud_profile_alert_threshold  The profile fraud threshold (percent out of 100)
 *                                                  at which point an alerts will be generated for this account
 *                                                  when changes are made to it. Applicable only if the
 *                                                  "affiliate_fraud_profile" Preference is enabled.
 *                                                  For more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202267053-Affiliate-Profile-Fraud.
 * @property string $fraud_profile_block_threshold  The profile fraud threshold (percent out of 100)
 *                                                  at which point the account will be automatically blocked
 *                                                  (status set to "blocked"). Applicable only if the
 *                                                  "affiliate_fraud_profile" Preference is enabled.
 *                                                  For more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202267053-Affiliate-Profile-Fraud.
 * @property string $fraud_profile_score            Profile fraud score for the Affiliate.
 *                                                  Applicable only if the "affiliate_fraud_profile"
 *                                                  Preference is enabled. This should not be manually
 *                                                  updated/overridden as the API will update this score automatically.
 *                                                  For more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202267053-Affiliate-Profile-Fraud.
 * @property string $id                             Id of unique object identifier for the Affiliate
 * @property string $modified                       The last time this account was updated
 * @property string $method_data                    DEPRECATED. Ignore the contents of this field.
 * @property string $other                          DEPRECATED. Ignore the contents of this field.
 * @property string $payment_method                 The method by which payments are made to the Affiliate.
 *                                                  The network may restrict which methods are allowed.
 *                                                  The payment method indicated by the
 *                                                  "affiliate_payment_method_default" Preference.
 * @property string $payment_terms                  The schedule by which invoices are generated for this Affiliate.
 *                                                  The terms indicated by the "affiliate_payment_terms_default"
 *                                                  Preference.
 * @property string $phone                          The Affiliate's phone number
 * @property string $ref_id                         An external reference ID for this account
 * @property string $referral_id                    The ID of the Affiliate account that referred this one
 * @property string $region                         The state/province of the account's physical address
 * @property string $signup_ip                      The IP address the Affiliate used to sign up from
 * @property string $status                         The status of the account
 * @property string $w9_filed                       Whether or not a W9 / W8-BEN is on file for this account
 * @property string $wants_alerts                   DEPRECATED. Ignore the contents of this field.
 * @property string $website                        DEPRECATED. Ignore the contents of this field.
 * @property string $zipcode                        The zipcode / postal code of the account's physical address
 *
 * @method PaymentMethod getPaymentMethod()
 * @method AffiliateUser getAffiliateUser()
 *
 * @package Unilead\HasOffers\Entity
 */
class Affiliate extends AbstractEntity
{
    use Deleted;
    use Blocked;

    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_DELETED = 'deleted';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var string
     */
    protected $target = 'Affiliate';

    /**
     * @var array
     */
    protected $methods = [
        'get'        => 'findById',
        'create'     => 'create',
        'update'     => 'update',
        'getAnswers' => 'getSignupAnswers',
    ];

    /**
     * @var array
     */
    protected $contain = [
        'PaymentMethod' => PaymentMethod::class,
        'AffiliateUser' => AffiliateUser::class
    ];

    /**
     * Unblock Affiliate in HasOffers.
     *
     * @return $this
     */
    public function unblock()
    {
        $this->hoClient->trigger("{$this->target}.unblock.before", [$this]);

        $this->status = self::STATUS_ACTIVE;
        $result = $this->save();

        $this->hoClient->trigger("{$this->target}.unblock.after", [$this]);

        return $result;
    }

    /**
     * Find sing up answers for given affiliate.
     *
     * @return mixed
     */
    public function getAnswers()
    {
        $data = $this->hoClient->apiRequest([
            'Method' => $this->methods['getAnswers'],
            'Target' => $this->target,
            'id'     => $this->id
        ]);

        $result = [];
        foreach ((array)$data as $answers) {
            foreach ($answers as $answer) {
                $result[] = [
                    'id'       => $answer['question_id'],
                    'question' => $answer['question'],
                    'answer'   => $answer['answer'],
                    'status'   => $answer['status']
                ];
            }
        }

        return $result;
    }
}
