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

use Item8\HasOffers\Traits\Deleted;

/* @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class Employee
 *
 * @property string $access                An array of permissions that the Employee has. This array contains a value
 *                                         "Brand" that grants base Employee permissions. Other possible permissions
 *                                         are: "Brand.stats" (Ability to view stats, graphs and reports),
 *                                         "Brand.billing" (Ability to view and maintain billing information),
 *                                         "Brand.offer_management" (Ability to edit offer information),
 *                                         "Brand.affiliate_management" (Ability to manage affiliate accounts),
 *                                         "Brand.lead_management" (Ability to manage the conversions for an
 *                                         account/offer), "Brand.global_management" (Ability to use permissions across
 *                                         all accounts and not just specifically accounts the Employee directly
 *                                         manages), "Brand.brand_management" (Ability to manage brand preferences and
 *                                         customization), "Brand.virtual_user" (Ability to virtual user as an
 *                                         affiliate),
 *                                         "Brand.file_management" (Ability to manage offer files),
 *                                         "Brand.offer_monitor_management" (Ability to manage offer monitors),
 *                                         "Brand.alert_management" (Ability to manage alerts), "Brand.dne_management"
 *                                         (Ability to manage DNE lists), "Brand.employee_management" (ability to
 *                                         add,edit,delete employees), "Brand.advertiser_management" (Ability to manage
 *                                         advertiser accounts).
 * @property string $aim                   The Employee's chat alias
 * @property string $billing_user          Whether or not the Employee can access and modify the Network's HasOffers
 *                                         billing details
 * @property string $cell_phone            The Employee's cell phone
 * @property string $email                 The Employee's email address
 * @property string $first_name            The Employee's first name
 * @property string $id                    A unique, auto-generated ID for the Employee
 * @property string $join_date             The date the Employee was created
 * @property string $last_login            The last time the Employee logged into the application
 * @property string $last_name             The Employee's last name
 * @property string $modified              The last time the Employee account was updated
 * @property string $password              The Employee's password
 * @property string $password_confirmation Provided on creation and must match the provided "password" field
 * @property string $permissions           The Employee's permissions stored as a bitmask. See the "access" field for
 *                                         readable permission names. This should not be set or updated directly;
 *                                         instead, the Employee::grantAccess and Employee::removeAccess API functions
 *                                         should be used to grant to, and revoke permissions from, Employees.
 * @property string $phone                 The Employee's phone number
 * @property string $photo                 A URL pointing to an image of the Employee's photograph
 * @property string $status                The Employee status
 * @property string $title                 The Employee's title
 * @property string $wants_alerts          DEPRECATED. This field should be ignored.
 *
 * @package Item8\HasOffers\Entity
 */
class Employee extends AbstractEntity
{
    use Deleted;

    const STATUS_ACTIVE  = 'active';
    const STATUS_DELETED = 'deleted';

    /**
     * @var string
     */
    protected $target = 'Employee';

    /**
     * @var array
     */
    protected $methods = [
        'get'    => 'findById',
        'create' => 'create',
        'update' => 'update',
    ];

    // TODO: Add exclude key access (Den)
}
