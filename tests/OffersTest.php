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
 * @package JBZoo\PHPUnit
 */
class OffersTest extends HasoffersPHPUnit
{
    public function testFindList()
    {
        $offers = $this->hoClient->get(Offers::class);
        $list = $offers->find();

        /** @var Offer $offer */
        $offer = $list[6];

        isSame('Super Galaxy Rush of Clans (iOS)', $offer->name);
    }
}
