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
 *
 * @package JBZoo\PHPUnit
 */
class EmployeeTest extends HasoffersPHPUnit
{
    protected $testId = '8';

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
        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $this->testId);

        is($this->testId, $employee->id);
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\Employee" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
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
        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $this->testId);
        is($this->testId, $employee->id);

        $employee->undefined_property;
    }

    public function testCanCreateEmployee()
    {
        $password = Str::random();
        $email = $this->faker->email;

        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class);
        $employee->first_name = $this->faker->firstName;
        $employee->last_name = $this->faker->lastName;
        $employee->phone = $this->faker->phoneNumber;
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

        $employee->delete(); // Clean up after test
    }

    public function testCanUpdateEmployee()
    {
        /** @var Employee $employeeBeforeSave */
        $employeeBeforeSave = $this->hoClient->get(Employee::class, $this->testId);

        $beforeCompany = $employeeBeforeSave->first_name;
        $employeeBeforeSave->first_name = $this->faker->name();

        $employeeBeforeSave->save();

        /** @var Employee $employeeAfterSave */
        $employeeAfterSave = $this->hoClient->get(Employee::class, $this->testId);
        isNotSame($beforeCompany, $employeeAfterSave->first_name);
    }

    public function testCanDeleteEmployee()
    {
        /** @var Employee $affiliateReset */
        $affiliateReset = $this->hoClient->get(Employee::class, $this->testId);
        $affiliateReset->status = 'active';
        $affiliateReset->save();

        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $this->testId);
        $employee->delete();

        /** @var Employee $employeeAfterSave */
        $employeeAfterSave = $this->hoClient->get(Employee::class, $this->testId);

        isSame(Employee::STATUS_DELETED, $employeeAfterSave->status);
    }
}
