<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace Item8\HasOffers\Entity;

use Item8\HasOffers\Traits\Blocked;
use Item8\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class AdvertiserUser
 *
 * @property array  $access                An array of permissions that the Advertiser User has. This array contains a
 *                                         value "Advertiser" that grants base Advertiser permissions. Other possible
 *                                         permissions are:
 *                                         "Advertiser.account_management" (Ability to manage account),
 *                                         "Advertiser.offer_management" (Ability to manage offers (limited)),
 *                                         "Advertiser.stats" (Ability to view stats, graphs, and reports),
 *                                         "Advertiser.user_management" (Ability to manage account users).
 * @property string $advertiser_id         The ID of the Advertiser  this User belongs to
 * @property string $cell_phone            The Advertiser  User's cell phone
 * @property string $email                 The Advertiser  User's email address
 * @property string $first_name            The Advertiser  User's first name
 * @property string $id                    A unique, auto-generated ID for the Advertiser  User
 * @property string $join_date             The date the Advertiser  User was created
 * @property string $last_login            The last time the Advertiser  User logged into the application
 * @property string $last_name             The Advertiser User's last name
 * @property string $modified              The last time the Advertiser  User account was updated
 * @property string $password              The Advertiser  User's password
 * @property string $password_confirmation Provided on creation and must match the provided "password" field
 * @property string $permissions           The Advertiser User's permissions stored as a bitmask. See the "access"
 *                                         field for readable permission names. This should not be set or updated
 *                                         directly; instead, the AdvertiserUser::grantAccess and
 *                                         AdvertiserUser::removeAccess API functions should be used to grant to, and
 *                                         revoke permissions from, Advertiser Users.
 * @property string $phone                 The Advertiser  User's phone number
 * @property string $status                The Advertiser  User status
 * @property string $title                 The Advertiser  User's title
 * @property string $wants_alerts          DEPRECATED. This field should be ignored.
 *
 * @package Item8\HasOffers\Entity
 */
class AdvertiserUser extends AbstractEntity
{
    use Deleted;
    use Blocked;

    const STATUS_ACTIVE  = 'active';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_DELETED = 'deleted';

    /**
     * @var string
     */
    protected $target = 'AdvertiserUser';

    /**
     * @var array
     */
    protected $excludedKeys = [
        'is_creator',
        'access',
        'modified',
    ];

    /**
     * @var array
     */
    protected $methods = [
        'get'    => 'findById',
        'create' => 'create',
        'update' => 'update',
    ];
}
