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

use Item8\HasOffers\Entity\Employee;
use Item8\HasOffers\Request\IntegratorApi;
use JBZoo\Utils\Env;
use JBZoo\Event\EventManager;

/**
 * Class HoIntegratorTest
 *
 * @package JBZoo\PHPUnit
 */
class IntegratorPHPUnit extends PHPUnit
{
    /**
     * @var IntegratorApi
     */
    protected $hoClient;

    /**
     * @var EventManager
     */
    protected $eManager;

    public function setUp()
    {
        parent::setUp();

        $this->hoClient = new IntegratorApi();
        $this->hoClient->setAuth(
            Env::get('HO_INTEGRATOR_API_CLIENT_ID'),
            Env::get('HO_INTEGRATOR_API_SECRET_TOKEN'),
            Env::get('HO_INTEGRATOR_API_INTEGRATOR_ID'),
            Env::get('HO_API_NETWORK_ID')
        );

        $httpUser = Env::get('HO_API_HTTP_USER');
        $httpPass = Env::get('HO_API_HTTP_PASS');
        if ($httpUser && $httpPass) {
            $this->hoClient->setHttpAuth($httpUser, $httpPass);
        }

        $this->hoClient->setRequestsLimit(Env::get('HO_API_REQUEST_LIMIT', 1, Env::VAR_INT));
        $this->hoClient->setTimeout(Env::get('HO_API_REQUEST_TIMEOUT', 1, Env::VAR_INT));

        $this->eManager = new EventManager();
        $this->hoClient->setEventManager($this->eManager);
        EventManager::setDefault($this->eManager);
    }

    public function testCanMakeRequest()
    {
        $employeeId = '8';

        isEmpty($this->hoClient->getJwtToken());

        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $employeeId);
        is($employeeId, $employee->id);

        $jwtToken = $this->hoClient->getJwtToken();
        $expireDate = $this->hoClient->getJwtExpireDate();
        $rawToken = $this->hoClient->getRawToken();
        isNotEmpty($jwtToken);
        isNotEmpty($expireDate);
        isNotEmpty($rawToken);

        $stats = $this->hoClient->apiRequest([
            'Target' => 'Report',
            'Method' => 'getStats',
            'fields' => ['Stat.year', 'Stat.revenue', 'Stat.payout', 'Stat.conversions'],
            'groups' => ['Stat.month'],
            'sort'   => ['Stat.year' => 'ASC', 'Stat.month' => 'ASC'],
            'totals' => false,
        ]);
        isNotEmpty($stats->get('data'));

        isSame($jwtToken, $this->hoClient->getJwtToken());
        isSame($expireDate, $this->hoClient->getJwtExpireDate());
        isSame($rawToken, $this->hoClient->getRawToken());
    }

    public function testInvalidJwtToken()
    {
        $employeeId = '8';

        $eventChecker = [];
        $this->eManager
            ->on('ho.*.save.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            })
            ->on('ho.api.request.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            });

        $this->hoClient->setJwtToken('test');

        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $employeeId);
        is($employeeId, $employee->id);

        isSame([
            'ho.api.request.before',
            'ho.api.request.after',
        ], $eventChecker);

        isSame(1, $this->hoClient->getRequestCounter());
    }

    /**
     * @expectedException \Item8\HasOffers\Request\Exception
     * @expectedExceptionMessage JWT is not valid or missing.
     */
    public function testAuthFailed()
    {
        $employeeId = '8';

        $this->hoClient = new IntegratorApi();
        $this->hoClient->setAuth(
            'test',
            'test',
            'test',
            'test'
        );

        // make request
        $employee = $this->hoClient->get(Employee::class, $employeeId);
        is($employeeId, $employee->id);
    }
}
