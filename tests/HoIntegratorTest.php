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

use Item8\HasOffers\Entities\Employees;
use Item8\HasOffers\Entity\Employee;
use JBZoo\Data\JSON;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\pt_BR\PhoneNumber;
use function JBZoo\Data\json;
use JBZoo\Data\PHPArray;
use JBZoo\Event\EventManager;
use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Response;
use JBZoo\Utils\Env;
use JBZoo\Utils\Str;
use Item8\HasOffers\HasOffersClient;
use Item8\HasOffers\Helper;

/**
 * Class HoIntegratorTest
 *
 * @package JBZoo\PHPUnit
 */
class HoIntegratorTest extends PHPUnit
{
    /**
     * @var HasOffersClient
     */
    protected $hoClient;

    /**
     * @var EventManager
     */
    protected $eManager;

    /**
     * @var Generator
     */
    protected $faker;

    public $integratorId = 'Item8ReadOnly';
    public $myClient     = 'Xy0wcH0eKC3BWndz7WvzYdzFpl2hSvbQ';
    public $mySecret     = 'PwDUNKIOBaoA9gbOnDRQZuJp4IA1LQG6gKyQF5m0Xtia7v5QG2ONpvp2z53s_BNa';

    public function setUp()
    {
        parent::setUp();

//        $this->hoClient = new HasOffersClient(HasOffersClient::MODE_INTEGRATOR);
//        $this->hoClient->setIntegratorAuth($this->myClient, $this->mySecret, $this->integratorId);

        // TODO: TOBE
//        $this->hoClient = (new HasOffersClient)::factory(HasOffersClient::MODE_INTEGRATOR);
//        $this->hoClient->setAuth($this->myClient, $this->mySecret, $this->integratorId);
//        $this->hoClient->get(Employees::class);

        // can't do this, because there is different params for
//        $this->hoClient = new HasOffersClient($this->myClient, $this->mySecret, $this->integratorId);
//        $this->hoClient->get(Employees::class);

//        $this->hoClient = new IntegratorApi();
//        $this->hoClient->setAuth($this->myClient, $this->mySecret, $this->integratorId);
//        $this->hoClient->get(Employees::class);

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

        $this->eManager
            ->on(
                'ho.api.request.before',
                function ($client, &$requestParams, &$url) {
                    $dumpFile = $this->getDumpFilename('request');
                    $requestParams['_ho_url'] = $url;
                    file_put_contents($dumpFile . '.json', '' . json($requestParams));
                }
            )
            ->on(
                'ho.api.request.after',
                function ($client, $jsonResult, Response $response) {
                    $dumpFile = $this->getDumpFilename('response');
                    file_put_contents($dumpFile . '.json', $response->getJSON());
                }
            );
    }

    public function testExampleRequest()
    {
        $jwt = httpRequest('https://integrator-auth.hasoffers.com/authorize', (new JSON([
            'client_id'     => trim($this->myClient),
            'client_secret' => trim($this->mySecret),
            'audience'      => 'BrandAPI'
        ]))->__toString(), 'POST')->getJSON();

        var_dump($jwt);


//        $response = httpRequest('https://integrator-api.hasoffers.com/Apiv3/json', [
//            'Format'       => 'json',
//            'NetworkId'    => 'item8demo',
//            'Target'       => 'Preference',
//            'Method'       => 'findAll',
//            'IntegratorId' => $this->integratorId,
//        ], 'GET', [
//            'headers' => [
//                'authorization' => "Bearer {$jwt}"
//            ]
//        ]);
//
//        print_r($response->getJSON());
    }

    public function testCanMakeRequest()
    {
        $employeeId = '8';

        /** @var Employee $employee */
        $employee = $this->hoClient->get(Employee::class, $employeeId);
        is($employeeId, $employee->id);
    }

    /**
     * @param $postfix
     * @return string
     */
    private function getDumpFilename($postfix)
    {
        $testName = $this->getTestName();
        $dumpFile = PROJECT_BUILD . "/dumps/{$testName}-{$postfix}-0";
        while (file_exists($dumpFile . '.json')) {
            $dumpFile = Str::inc($dumpFile, 'dash');
        }

        return $dumpFile;
    }

    /**
     * @return string
     */
    private function getTestName()
    {
        $trace = debug_backtrace();
        foreach ($trace as $traceRow) {
            if (strpos($traceRow['function'], 'test') === 0) {
                $testName = str_replace('test_', '', Str::splitCamelCase($traceRow['function'], '_'));
                $entity = str_replace([__NAMESPACE__ . '\\', 'Test'], '', static::class);

                return strtolower($entity . '_' . $testName);
            }
        }

        return 'undefined_test_name';
    }

    /**
     * @param $requestParams
     */
    protected function dumpMethodName($requestParams)
    {
        if ($requestParams['Target'] === 'Undefined' || $requestParams['Method'] === 'Undefined') {
            return;
        }

        $dumpFile = PROJECT_BUILD . '/all-methods.log';

        $allMethods = '';
        if (file_exists($dumpFile)) {
            $allMethods = file_get_contents($dumpFile);
        }

        $allMethods = Str::parseLines($allMethods, true);

        $methodName = "{$requestParams['Target']}::{$requestParams['Method']}";
        $allMethods[$methodName] = $methodName;
        ksort($allMethods);

        file_put_contents($dumpFile, implode(PHP_EOL, $allMethods) . PHP_EOL);
    }
}
