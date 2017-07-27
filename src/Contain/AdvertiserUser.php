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

use Unilead\HasOffers\Entity\Advertiser;

/**
 * Class AdvertiserUser
 *
 * @property string access                   Array    An array of permissions that the Advertiser User has. This array
 *           contains a value "Advertiser" that grants base Advertiser permissions. Other possible permissions are:
 *           "Advertiser.account_management" (Ability to manage account), "Advertiser.offer_management" (Ability to
 *           manage offers (limited)), "Advertiser.stats" (Ability to view stats, graphs, and reports),
 *           "Advertiser.user_management" (Ability to manage account users).
 * @property string advertiser_id            Integer    The ID of the Advertiser this User belongs to
 * @property string cell_phone               Nullable String    The Advertiser User's cell phone
 * @property string email                    String    The Advertiser User's email address
 * @property string first_name               Nullable String    The Advertiser User's first name
 * @property string id                       Integer    A unique, auto-generated ID for the Advertiser User
 * @property string join_date                Datetime    The date the Advertiser User was created
 * @property string last_login               Nullable Datetime    The last time the Advertiser User logged into the
 *           application
 * @property string last_name                Nullable String    The Advertiser User's last name
 * @property string modified                 Datetime    The last time the Advertiser User account was updated
 * @property string password                 String    The Advertiser User's password
 * @property string password_confirmation    String    Provided on creation and must match the provided "password"
 *           field
 * @property string permissions              Integer    The Advertiser User's permissions stored as a bitmask. See the
 *           "access" field for readable permission names. This should not be set or updated directly; instead, the
 *           AdvertiserUser::grantAccess and AdvertiserUser::removeAccess API functions should be used to grant to, and
 *           revoke permissions from, Advertiser Users.
 * @property string phone                    Nullable String    The Advertiser User's phone number
 * @property string status                   String    The Advertiser User status
 * @property string title                    Nullable String    The Advertiser User's title
 * @property string wants_alerts             Boolean    DEPRECATED. This field should be ignored.
 *
 * @package Unilead\HasOffers
 */
class AdvertiserUser extends AbstractClientUser
{
    /**
     * @var Advertiser
     */
    protected $parentEntity;

    /**
     * @var string
     */
    protected $target = 'AdvertiserUser';
}
