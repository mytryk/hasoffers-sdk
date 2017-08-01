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

use Unilead\HasOffers\Entities\Offers;
use Unilead\HasOffers\Entity\Offer;

/**
 * Class OffersTest
 *
 * @package JBZoo\PHPUnit
 */
class OffersTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        $offers = $this->hoClient->get(Offers::class);
        $list = $offers->find();

        /** @var Offer $offer */
        $offer = $list[4];

        isSame('Beasts of Dungeons (Android)', $offer->name);

        isSame((float)1000, $offer->getMonthlyRevenueCap());
        isSame((float)1600, $offer->getBudget());
        isSame((float)1000, $offer->getMonthlyCapAmount());
        isSame((float)625, $offer->getMonthlyConversionsCap());

        isSame((float) 100, (float) $offer->conversion_cap);
        isSame((float) 1000, (float) $offer->monthly_conversion_cap);
        isSame((float) 10000, (float) $offer->lifetime_conversion_cap);
        isSame((float)100, (float)$offer->payout_cap);
        isSame((float) 1000, (float) $offer->monthly_payout_cap);
        isSame((float) 5000, (float) $offer->lifetime_payout_cap);
        isSame((float) 100, (float) $offer->revenue_cap);
        isSame((float) 1000, (float) $offer->monthly_revenue_cap);
        isSame((float) 10000, (float) $offer->lifetime_revenue_cap);

        isSame('Android', $offer->getRuleTargeting()[0]['Name']);
        isSame('Android operating system', $offer->getRuleTargeting()[0]['Description']);
        isSame('Android', $offer->getRuleTargeting()[0]['Platform']);

        isTrue($offer->getGoal()->data()->getArrayCopy());
        $goals = $offer->getGoal()->data()->getArrayCopy();
        isSame('2', $goals[0]['id']);
        isSame('Tutorial', $goals[0]['name']);
        isSame('0.70000', $goals[0]['default_payout']);
        isSame('1.00000', $goals[0]['max_payout']);

        isSame('US;RU', $offer->getCountriesCodes());
    }
}
