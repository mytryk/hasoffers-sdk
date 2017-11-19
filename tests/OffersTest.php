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

use Unilead\HasOffers\Entities\Offers;
use Unilead\HasOffers\Entity\Offer;

/**
 * Class OffersTest
 *
 * @package JBZoo\PHPUnit
 */
class OffersTest extends HasoffersPHPUnit
{
    protected $testId = '4';

    public function testFindList()
    {
        $offers = $this->hoClient->get(Offers::class);
        $list = $offers->find();

        /** @var Offer $offer */
        $offer = $list[$this->testId];

        isTrue($offer->getGoal()->data()->getArrayCopy());
        $goals = $offer->getGoal()->data()->getArrayCopy();
        isSame('2', $goals[0]['id']);
        isSame('Install', $goals[0]['name']);
        isSame('1.00000', $goals[0]['default_payout']);
        isSame('2.00000', $goals[0]['max_payout']);

        isSame('RU;US', $offer->getCountriesCodes());

        isSame('Beasts of Dungeons (iOS)', $offer->name);


        isSame('iOS', $offer->getRuleTargeting()[0]['Name']);
        isSame('iOS operating system', $offer->getRuleTargeting()[0]['Description']);
        isSame('iOS', $offer->getRuleTargeting()[0]['Platform']);

        isSame((float)0, $offer->getMonthlyRevenueCap());
        isSame((float)0, $offer->getBudget());
        isSame((float)0, $offer->getMonthlyCapAmount());
        isSame((float)0, $offer->getMonthlyConversionsCap());
        isSame((float)0, (float)$offer->conversion_cap);
        isSame((float)0, (float)$offer->monthly_conversion_cap);
        isSame((float)0, (float)$offer->lifetime_conversion_cap);
        isSame((float)0, (float)$offer->payout_cap);
        isSame((float)0, (float)$offer->monthly_payout_cap);
        isSame((float)0, (float)$offer->lifetime_payout_cap);
        isSame((float)0, (float)$offer->revenue_cap);
        isSame((float)0, (float)$offer->monthly_revenue_cap);
        isSame((float)0, (float)$offer->lifetime_revenue_cap);

        // TODO: Fix content on HO for offer=4 (den)
        // Old tests (before moving to item8demo)
        //isSame((float)1000, $offer->getMonthlyRevenueCap());
        //isSame((float)1600, $offer->getBudget());
        //isSame((float)1000, $offer->getMonthlyCapAmount());
        //isSame((float)625, $offer->getMonthlyConversionsCap());
        //isSame((float)100, (float)$offer->conversion_cap);
        //isSame((float)1000, (float)$offer->monthly_conversion_cap);
        //isSame((float)10000, (float)$offer->lifetime_conversion_cap);
        //isSame((float)100, (float)$offer->payout_cap);
        //isSame((float)1000, (float)$offer->monthly_payout_cap);
        //isSame((float)5000, (float)$offer->lifetime_payout_cap);
        //isSame((float)100, (float)$offer->revenue_cap);
        //isSame((float)1000, (float)$offer->monthly_revenue_cap);
        //isSame((float)10000, (float)$offer->lifetime_revenue_cap);
    }
}
