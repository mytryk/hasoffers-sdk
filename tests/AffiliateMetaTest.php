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

use Item8\HasOffers\Entity\Affiliate;

/**
 * Class AffiliateMetaTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateMetaTest extends HasoffersPHPUnit
{
    public const AFFILIATE_ID            = 3481;
    public const AFFILIATE_ID_FOR_UPDATE = 3052;

    public function testGetMeta(): void
    {
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, self::AFFILIATE_ID);
        $affiliateMeta = $affiliate->getAffiliateMeta();

        isSame($affiliate->id, $affiliateMeta->affiliate_id);
        isNull($affiliateMeta->ssn_tax);
    }

    /**
     * @expectedException           \Item8\HasOffers\Exception
     * @expectedExceptionMessage    Property "id" read only in Item8\HasOffers\Entity\Affiliate
     */
    public function testCanUpdateMeta(): void
    {
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, self::AFFILIATE_ID_FOR_UPDATE);

        $affiliateMeta = $affiliate->getAffiliateMeta();
        $newSsnTax = '' . random_int(1, 50);

        isNotSame($newSsnTax, $affiliateMeta->ssn_tax);
        $affiliateMeta->ssn_tax = $newSsnTax;
        $affiliateMeta->save();

        isSame($newSsnTax, $affiliateMeta->ssn_tax);
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Item8\HasOffers\Entity\AffiliateMeta
     * @throws \Item8\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty(): void
    {
        /** @var Affiliate $advertiser */
        $advertiser = $this->hoClient->get(Affiliate::class, self::AFFILIATE_ID);
        $affiliateMeta = $advertiser->getAffiliateMeta();
        is(self::AFFILIATE_ID, $affiliateMeta->affiliate_id);

        $affiliateMeta->undefined_property;
    }
}
