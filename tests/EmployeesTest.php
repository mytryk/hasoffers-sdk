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

namespace JBZoo\PHPUnit;

use Unilead\HasOffers\Entities\Employees;
use Unilead\HasOffers\Entity\Employee;

/**
 * Class EmployeesTest
 * @package JBZoo\PHPUnit
 */
class EmployeesTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        $employee = $this->hoClient->get(Employees::class);
        $list = $employee->find();

        /** @var Employee $employee */
        $employee = $list[14];

        isSame('Advertiser', $employee->first_name);
        isSame('Account Manager', $employee->last_name);
        isSame('advmanager@4tune.systems', $employee->email);
    }
}
