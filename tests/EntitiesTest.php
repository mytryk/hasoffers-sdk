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

use Unilead\HasOffers\Entities\Affiliates;
use Unilead\HasOffers\Entities\Employees;
use Unilead\HasOffers\Entity\Employee;

/**
 * Class EntitiesTest
 *
 * @package JBZoo\PHPUnit
 */
class EntitiesTest extends HasoffersPHPUnit
{
    public function testLimit()
    {
        $entities = $this->hoClient->get(Employees::class);
        $list = $entities->find(['limit' => 11]);

        end($list);
        $key = key($list);

        /** @var Employee $employee */
        $employee = $list[$key];
        isSame($key, (int)$employee->id);

        isSame(11, count($list));
        isSame(1, $this->hoClient->getRequestCounter());
    }

    public function testLimitByPages()
    {
        $requestCounter = 0;
        $this->eManager->on('ho.api.request.after', function () use (&$requestCounter) {
            $requestCounter++;
        });

        $entities = $this->hoClient->get(Employees::class);
        $entities->setPageSize(5);
        $list = $entities->find(['limit' => 11]);

        end($list);
        $key = key($list);

        /** @var Employee $employee */
        $employee = $list[$key];
        isSame($key, (int)$employee->id);

        isSame(3, $this->hoClient->getRequestCounter());
        isSame($requestCounter, $this->hoClient->getRequestCounter());
        isSame(11, count($list));
    }

    public function testFindAll()
    {
        $affiliates = $this->hoClient->get(Affiliates::class);
        $list = $affiliates->find();

        isTrue(count($list) > 20);
    }
}
