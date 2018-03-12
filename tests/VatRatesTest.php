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

use Unilead\HasOffers\Entities\VatRates;
use Unilead\HasOffers\Entity\VatRate;

/**
 * Class VatRatesTest
 *
 * @package JBZoo\PHPUnit
 */
class VatRatesTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        $rates = $this->hoClient->get(VatRates::class);
        $list = $rates->find();

        /** @var VatRate $vatRate */
        $vatRate = $list[2];

        isSame(18.00, (float)$vatRate->rate);
        isSame(VatRateTest::EXISTED_VAT_RATE_ID, (int)$vatRate->id);
    }
}
