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

use JBZoo\Utils\Str;
use JBZoo\Data\Data;
use Unilead\HasOffers\Entity\Employee;

/**
 * Class EmployeeTest
 * @package JBZoo\PHPUnit
 */
class EmployeeTest extends HasoffersPHPUnit
{
    public function testCreatingEmployeeWays()
    {
        $employee1 = $this->hoClient->get(Employee::class); // recommended!
        $employee2 = $this->hoClient->get('Employee');
        $employee3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\Employee');
        $employee4 = new Employee();
        $employee4->setClient($this->hoClient);

        isClass(Employee::class, $employee1);
        isClass(Employee::class, $employee2);
        isClass(Employee::class, $employee3);
        isClass(Employee::class, $employee4);

        isNotSame($employee1, $employee2);
        isNotSame($employee1, $employee3);
    }

    public function testCanGetEmployeeById()
    {
        $someId = '8';
        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $someId);

        is($someId, $employee->id);
    }

    /**
     * @expectedExceptionMessage Missing required argument: data
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $employee = $this->hoClient->get(Employee::class);
        $employee->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\Employee
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $someId = '8';
        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $someId);
        is($someId, $employee->id);

        $employee->undefined_property;
    }

    public function testCanCreateEmployee()
    {
        $password = Str::random(13);
        $email = Str::random(10) . '@' . Str::random(5) . '.com';

        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class);
        $employee->first_name = 'Test';
        $employee->last_name = 'User';
        $employee->phone = '+7 845 845 84 54';
        $employee->email = $email;
        $employee->password = $password;
        $employee->password_confirmation = $password;
        $employee->save();

        /** @var Employee $employeeCheck */
        $employeeCheck = $this->hoClient->get(Employee::class, $employee->id);

        isSame($employee->id, $employeeCheck->id); // Check is new id bind to object
        isSame($employee->first_name, $employeeCheck->first_name);
        isSame($employee->last_name, $employeeCheck->last_name);
        isSame($employee->phone, $employeeCheck->phone);
    }

    public function testCanUpdateEmployee()
    {

        skip('password is sent but should not.');
        /** @var Employee $employeeBeforeSave */
        $employeeBeforeSave = $this->hoClient->get(Employee::class, 8);

        $beforeCompany = $employeeBeforeSave->first_name;
        $employeeBeforeSave->first_name = Str::random();
        $employeeBeforeSave->save();

        /** @var Employee $employeeAfterSave */
        $employeeAfterSave = $this->hoClient->get(Employee::class, 8);
        isNotSame($beforeCompany, $employeeAfterSave->first_name);
    }

    public function testCanDeleteEmployee()
    {
        /** @var Employee $affiliate */
        $affiliate = $this->hoClient->get(Employee::class, 8);
        $affiliate->delete();

        /** @var Employee $affiliateAfterSave */
        $affiliateAfterSave = $this->hoClient->get(Employee::class, 8);

        isSame(Employee::STATUS_DELETED, $affiliateAfterSave->status);
    }
}
