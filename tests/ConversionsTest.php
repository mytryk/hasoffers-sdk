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

use JBZoo\Utils\Env;
use Unilead\HasOffers\Entities\Conversions;
use Unilead\HasOffers\Entity\Conversion;

/**
 * Class ConversionsTest
 *
 * @package JBZoo\PHPUnit
 */
class ConversionsTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        $offers = $this->hoClient->get(Conversions::class);
        $list = $offers->find([
            'sort'  => ['id' => 'asc'],
            'limit' => 1,
        ]);

        isSame('2', $list[2][Conversion::ID]);
        isSame('2', $list[2][Conversion::AFFILIATE_ID]);
        isSame('504', $list[2][Conversion::ADVERTISER_ID]);
        isSame('8', $list[2][Conversion::OFFER_ID]);
        isSame('0', $list[2][Conversion::GOAL_ID]);
        isSame('2017-09-06 14:30:00', $list[2][Conversion::DATETIME]);
        isSame('1.65000', $list[2][Conversion::PAYOUT]);
        isSame('2.50000', $list[2][Conversion::REVENUE]);
        isSame('approved', $list[2][Conversion::STATUS]);
        isSame('1', $list[2][Conversion::IS_ADJUSTMENT]);
        isSame('cpa_flat', $list[2][Conversion::PAYOUT_TYPE]);
        isSame('cpa_flat', $list[2][Conversion::REVENUE_TYPE]);
        isSame('USD', $list[2][Conversion::CURRENCY]);
    }

    public function testLoad100kItems()
    {
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
            ->on('ho.api.request.after',
                function ($client, $json, $response, $requestParams, $url) use (&$cleanUrl) {
                    $requestParams['NetworkToken'] = Env::get('HO_API_NETWORK_TOKEN');
                    $cleanUrl = $url . '?' . http_build_query($requestParams);
                });

        /** @var Conversions $offers */
        $offers = $this->hoClient
            ->setTimeout(0)
            ->setRequestsLimit(0)
            ->lastResponseMode(false)
            ->get(Conversions::class)
            ->setPageSize($pageSize);

        \JBDump::log('start');

        $list = $offers->find(['sort' => ['id' => 'asc'], 'limit' => $limit]);

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
}
