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

use JBZoo\Profiler\Benchmark;
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
        $requestCounter = 0;

        $this->eManager->on(
            'ho.api.request.after',
            function ($hoClient, $realResp, $response, $requestParams) use (&$requestCounter) {
                isSame(1, $requestParams['limit']);
                $requestCounter++;
            }
        );

        $list = $this->conversions->find([
            'sort'  => ['id' => 'asc'],
            'limit' => 1,
        ]);

        isSame(1, $requestCounter);

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

        isNull($this->hoClient->getLastResponse());
    }
}
