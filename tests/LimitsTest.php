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

use JBZoo\Profiler\Benchmark;
use JBZoo\Utils\Env;
use Unilead\HasOffers\Entities\Conversions;
use Unilead\HasOffers\Entity\Conversion;

/**
 * Class LimitsTest
 *
 * @package JBZoo\PHPUnit
 */
class LimitsTest extends HasoffersPHPUnit
{
    /**
     * @var Conversions
     */
    protected $conversions;

    public function setUp()
    {
        parent::setUp();
        $this->conversions = $this->hoClient->get(Conversions::class);
    }

    public function testFindOneRow()
    {
        $this->eManager->on(
            'ho.api.request.after',
            function ($hoClient, $realResp, $response, $requestParams) {
                isSame(1, $requestParams['limit']);
            }
        );

        $list = $this->conversions->find([
            'sort'  => ['id' => 'asc'],
            'limit' => 1,
        ]);

        isSame(1, $this->hoClient->getRequestCounter());
        isSame('2', $list[2][Conversion::ID]);
        isNull($this->hoClient->getLastResponse());
    }

    public function testFindOneRowById()
    {
        $this->eManager->on(
            'ho.api.request.after',
            function ($hoClient, $realResp, $response, $requestParams) {
                isSame(100000, $requestParams['limit']);
            }
        );

        $list = $this->conversions->find(['filters' => ['id' => 2]]);

        isSame(1, $this->hoClient->getRequestCounter());
        isSame('2', $list[2][Conversion::ID]);
        isSame(1, count($list));
    }

    public function testLoadConversionsByLimit()
    {
        $customLimit = 62160;

        isSame(100000, $this->conversions->getPageSize());
        $list = $this->conversions->find(['limit' => $customLimit]);

        isSame($customLimit, count($list));
    }

    public function testLoadByLimitWithPageSize()
    {
        $customLimit = 49999;
        $customPageSize = 20000;
        $expectedRequestCount = (int)ceil($customLimit / $customPageSize);

        $this->eManager->on(
            'ho.api.request.after',
            function ($hoClient, $realResp, $response, $requestParams) use ($customPageSize) {
                isSame($customPageSize, $requestParams['limit']);
            }
        );

        $list = $this->conversions
            ->setPageSize($customPageSize)
            ->find(['limit' => $customLimit]);

        isSame($expectedRequestCount, $this->hoClient->getRequestCounter());
        isSame($customLimit, count($list));
    }

    public function testTryToLoadUnlimit()
    {
        $list = $this->conversions->find([
            'fields' => ['id'],
        ]);
        isTrue(62165 >= count($list));
    }

    public function testTryToLoadLessPageSize()
    {
        $this->eManager->on(
            'ho.api.request.after',
            function ($hoClient, $realResp, $response, $requestParams) {
                isSame(50, $requestParams['limit']);
            }
        );

        $list = $this->conversions
            ->setPageSize(100)
            ->find(['limit' => 50]);

        isSame(1, $this->hoClient->getRequestCounter());
        isSame(50, count($list));
    }

    public function testTryToLoadPageSizeEqLimit()
    {
        $this->eManager->on(
            'ho.api.request.after',
            function ($hoClient, $realResp, $response, $requestParams) {
                isSame(11, $requestParams['limit']);
            }
        );

        $list = $this->conversions
            ->setPageSize(11)
            ->find(['limit' => 11]);

        isSame(1, $this->hoClient->getRequestCounter());
        isSame(11, count($list));
    }

    public function testCount()
    {
        $count = $this->conversions->count();
        isTrue(62165 >= $count);
    }

    public function testProfiling()
    {
        skip('This test only for profiling');
        $limit = 50000;
        $pageSize = 50000;

        $profiler = function () {
            $args = func_get_args();
            $eventName = end($args);
            \JBDump::mark($eventName);
        };

        $cleanUrl = '';

        $this->eManager
            ->on('ho.*', $profiler)
            ->on('ho.*.*', $profiler)
            ->on('ho.*.*.*', $profiler)
            ->on('ho.*.*.*.*', $profiler)
            ->on('ho.*.*.*.*.*', $profiler)
            ->on('ho.*.*.*.*.*.*', $profiler)
            ->on('ho.api.request.after', function ($client, $json, $response, $requestParams, $url) use (&$cleanUrl) {
                $requestParams['NetworkToken'] = Env::get('HO_API_NETWORK_TOKEN');
                $cleanUrl = $url . '?' . http_build_query($requestParams);
            });

        \JBDump::log('start');

        $list = $this->conversions
            ->setPageSize($pageSize)
            ->find(['sort' => ['id' => 'asc'], 'limit' => $limit]);

        isSame($limit, count($list));

        \JBDump::mark('unset-list-start');
        unset($list);
        \JBDump::mark('unset-list-finish');
        \JBDump::log('end-sdk');
        //return;

        // ///////////////////////////////////////////////////////////////////////////////////
        \JBDump::mark('clean-start');

        $jsonData = json_decode(file_get_contents($cleanUrl), true);
        \JBDump::mark('clean-loaded');

        isSame($limit, count($jsonData['response']['data']['data']));

        \JBDump::mark('unset-list-start');
        unset($jsonData);
        \JBDump::mark('clean-end');

        \JBDump::log('end');
    }

    public function testBenchmark()
    {
        skip('Experimental benchmark');

        // Prepare before benchmark
        $limit = 10000;
        $pageSize = 10000;

        $this->conversions->setPageSize($pageSize);

        $cleanUrl = '';
        $this->eManager->on(
            'ho.api.request.after',
            function ($client, $json, $response, $requestParams, $url) use (&$cleanUrl) {
                $requestParams['NetworkToken'] = Env::get('HO_API_NETWORK_TOKEN');
                $cleanUrl = $url . '?' . http_build_query($requestParams);
            }
        );

        $sdkResult = [];
        $cleanResult = [];

        // Run it!
        Benchmark::compare([
            'sdk'   => function () use ($limit, &$sdkResult) {
                $sdkResult = $this->conversions->find(['sort' => ['id' => 'asc'], 'limit' => $limit]);
                isSame($limit, count($sdkResult));
            },
            'clean' => function () use (&$cleanUrl, $limit, &$cleanResult) {
                $cleanResult = json_decode(file_get_contents($cleanUrl), true);
                isSame($limit, count($cleanResult['response']['data']['data']));
            },
        ], ['name' => 'Load 50k', 'count' => 1]);

        isSame($limit, count($sdkResult));
        isSame($limit, count($cleanResult['response']['data']['data']));
    }
}
