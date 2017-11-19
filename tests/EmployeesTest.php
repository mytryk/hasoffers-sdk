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

namespace JBZoo\PHPUnit;

use Unilead\HasOffers\Entities\Employees;
use Unilead\HasOffers\Entity\Employee;

/**
 * Class EmployeesTest
 *
 * @package JBZoo\PHPUnit
 */
class EmployeesTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testFindList()
    {
        $employee = $this->hoClient->get(Employees::class);
        $list = $employee->find();

        /** @var Employee $employee */
        $employee = $list[$this->testId];

        isSame('Dmitry', $employee->first_name);
        isSame('Semenov', $employee->last_name);
        isSame('dmitry.semenov@item8.io', $employee->email);
    }
}
