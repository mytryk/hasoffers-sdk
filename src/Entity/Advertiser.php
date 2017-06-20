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
 * Class Advertiser
 *
 * @property string $account_manager_id             The ID of the Employee from the Network who is assigned as
 *                                                  the account manager for the Advertiser
 * @property string $address1                       The first line of the account's physical address
 * @property string $address2                       The second line of the account's physical address
 * @property string $city                           The city of the account's physical address
 * @property string $company                        The company name of the Advertiser
 * @property string $conversion_security_token      The Advertiser Security Token that must be passed by the advertiser
 *                                                  in order to register a conversion. Applicable only if the
 *                                                  "enable_advertiser_security_token" Preference is enabled.
 *                                                  For more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202759308-Advertiser-Security-Token
 * @property string $country                        The country of the account's physical address
 * @property string $date_added                     The date this account was created
 * @property string $fax                            The fax number of the Advertiser
 * @property string $id                             Id of unique object identifier for the Advertiser
 * @property string $modified                       The last time this account was updated
 * @property string $other                          DEPRECATED. Ignore the contents of this field.
 * @property string $phone                          The Advertiser's phone number
 * @property string $ref_id                         An external reference ID for this account
 * @property string $region                         The state/province of the account's physical address
 * @property string $signup_ip                      The IP address the Advertiser used to sign up from
 * @property string $status                         The status of the account
 * @property string $tmp_token                      Holds an Advertiser Security Token without enforcing it. This is
 *                                                  where the security token should be placed until it can be
 *                                                  communicated to the Advertiser and they update their application to
 *                                                  pass it. After they update their code the token should be copied to
 *                                                  the "conversion_security_token" field to enforce it. Applicable
 *                                                  only
 *                                                  if the "enable_advertiser_security_token" Preference is enabled.
 *                                                  For
 *                                                  more information, see:
 *                                                  http://support.hasoffers.com/hc/en-us/articles/202759308-Advertiser-Security-Token
 * @property string $wants_alerts                   DEPRECATED. Ignore the contents of this field.
 * @property string $website                        DEPRECATED. Ignore the contents of this field.
 * @property string $zipcode                        The zipcode / postal code of the account's physical address
 *
 * @package      Unilead\HasOffers
 */
class Advertiser extends AbstractEntity
{
    use Deleted;

    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_DELETED = 'deleted';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var string
     */
    protected $target = 'Advertiser';

    /**
     * @var array
     */
    protected $contain = [];

    /**
     * @return $this
     */
    public function restore()
    {
        $this->status = self::STATUS_ACTIVE;

        return $this->save();
    }
}
