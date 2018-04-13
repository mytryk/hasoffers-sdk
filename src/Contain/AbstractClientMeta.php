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

/**
 * Class AbstractClientUser
 *
 * @property string access                   Array    An array of permissions that the Affiliate User has. This array
 *           contains a value "Affiliate" that grants base Affiliate permissions. Other possible permissions are:
 *           "Affiliate.account_management" (Ability to manage account users), "Affiliate.API" (Permission to request
 *           and use Affiliate API V3), "Affiliate.offer_management" (Ability to manage offers), "Affiliate.stats"
 *           (Ability to view stats, graphs and reports), "Affiliate.user_management" (Ability to manage account
 *           users).
 * @property string affiliate_id             Integer    The ID of the Affiliate this User belongs to
 * @property string cell_phone               Nullable String    The Affiliate User's cell phone
 * @property string email                    String    The Affiliate User's email address
 * @property string first_name               Nullable String    The Affiliate User's first name
 * @property string id                       Integer    A unique, auto-generated ID for the Affiliate User
 * @property string This                     parameter is non-writable
 * @property string join_date                Datetime    The date the Affiliate User was created
 * @property string last_login               Nullable Datetime    The last time the Affiliate User logged into the
 *           application
 * @property string last_name                Nullable String    The Affiliate User's last name
 * @property string modified                 Datetime    The last time the Affiliate User account was updated
 * @property string password                 String    The Affiliate User's password
 * @property string password_confirmation    String    Provided on creation and must match the provided "password"
 *           field
 * @property string permissions              Integer    The Affiliate User's permissions stored as a bitmask. See the
 *           "access" field for readable permission names. This should not be set or updated directly; instead, the
 *           AffiliateUser::grantAccess and AffiliateUser::removeAccess API functions should be used to grant to, and
 *           revoke permissions from, Affiliate Users.
 * @property string phone                    Nullable String    The Affiliate User's phone number
 * @property string status                   String    The Affiliate User status
 * @property string title                    Nullable String    The Affiliate User's title
 * @property string wants_alerts             Boolean    DEPRECATED. This field should be ignored.
 *
 * @package Item8\HasOffers
 */
abstract class AbstractClientMeta extends AbstractContain
{
    protected $billingName = '';

    /**
     * Save changed client meta
     *
     * @param array $properties
     * @return bool
     * @throws \Item8\HasOffers\Exception
     */
    public function save(array $properties = [])
    {
        if (count($properties) !== 0) {
            return $this->mergeData($properties)->save();
        }

        $savedData = array_merge(
            $this->getChangedFields(),
            $this->data()->getArrayCopy()
        );
        $dataForUpdate = $this->removeExcludedKeys($savedData);

        if (empty($dataForUpdate)) {
            return false;
        }

        $this->hoClient->trigger("{$this->target}.save.before", [$this, &$dataForUpdate]);

        $result = $this->hoClient->apiRequest([
            'Target' => $this->billingName,
            'Method' => 'updateTaxInfo',
            'id'     => $this->parentEntity->id,
            'data'   => $dataForUpdate,
        ]);

        if ($result->get('0', null, 'bool')) {
            $newData = array_merge($this->data()->getArrayCopy(), $dataForUpdate);
            $this->bindData($newData);
            $this->origData = $newData;
            $this->changedData = [];

            $this->hoClient->trigger("{$this->target}.save.after", [$this, $newData]);

            return true;
        }

        return false;
    }
}
