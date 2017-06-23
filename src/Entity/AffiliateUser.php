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
use Unilead\HasOffers\Traits\Blocked;
use Unilead\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class AffiliateUser
 *
 * @property array  $access                An array of permissions that the Affiliate User has. This array contains a
 *                                         value "Affiliate" that grants base Affiliate permissions. Other possible
 *                                         permissions are:
 *                                         "Affiliate.account_management" (Ability to manage account users),
 *                                         "Affiliate.API" (Permission to request and use Affiliate API V3),
 *                                         "Affiliate.offer_management" (Ability to manage offers), "Affiliate.stats"
 *                                         (Ability to view stats, graphs and reports), "Affiliate.user_management"
 *                                         (Ability to manage account users).
 * @property string $affiliate_id          The ID of the Affiliate this User belongs to
 * @property string $cell_phone            The Affiliate User's cell phone
 * @property string $email                 The Affiliate User's email address
 * @property string $first_name            The Affiliate User's first name
 * @property string $id                    A unique, auto-generated ID for the Affiliate User
 * @property string $join_date             The date the Affiliate User was created
 * @property string $last_login            The last time the Affiliate User logged into the application
 * @property string $last_name             The Affiliate User's last name
 * @property string $modified              The last time the Affiliate User account was updated
 * @property string $password              The Affiliate User's password
 * @property string $password_confirmation Provided on creation and must match the provided "password" field
 * @property string $permissions           The Affiliate User's permissions stored as a bitmask. See the "access" field
 *                                         for readable permission names. This should not be set or updated directly;
 *                                         instead, the AffiliateUser::grantAccess and AffiliateUser::removeAccess API
 *                                         functions should be used to grant to, and revoke permissions from, Affiliate
 *                                         Users.
 * @property string $phone                 The Affiliate User's phone number
 * @property string $status                The Affiliate User status
 * @property string $title                 The Affiliate User's title
 * @property string $wants_alerts          DEPRECATED. This field should be ignored.
 *
 * @package Unilead\HasOffers\Entity
 */
class AffiliateUser extends AbstractEntity
{
    use Deleted;
    use Blocked;

    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_DELETED = 'deleted';

    /**
     * @var string
     */
    protected $target = 'AffiliateUser';

    /**
     * @var array
     */
    protected $excludeKeys = ['is_creator', 'access', 'modified'];

    /**
     * @var array
     */
    protected $methods = [
        'get'    => 'findById',
        'create' => 'create',
        'update' => 'update',
    ];
}
