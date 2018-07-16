<?php
/**
 * Item8 | HasOffers
 *
 * This file is part of the Item8 Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     GNU GPL
 * @copyright   Copyright (C) Item8, All rights reserved.
 * @link        https://item8.io
 */

namespace JBZoo\PHPUnit;

use Item8\HasOffers\Entities\VatRates;
use Item8\HasOffers\Entity\VatRate;

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
