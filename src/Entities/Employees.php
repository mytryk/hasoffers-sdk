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

namespace Unilead\HasOffers\Entities;

use Unilead\HasOffers\Entity\Employee;

/**
 * Class Employees
 *
 * @package Unilead\HasOffers\Entities
 */
class Employees extends AbstractEntities
{
    /**
     * @var string
     */
    protected $target = 'Employee';

    /**
     * @var string
     */
    protected $className = Employee::class;
}