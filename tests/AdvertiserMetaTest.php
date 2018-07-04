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

use Item8\HasOffers\Entity\Advertiser;
use Item8\HasOffers\Contain\AdvertiserMeta;

/**
 * Class AdvertiserMetaTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserMetaTest extends HasoffersPHPUnit
{
    public const ADVERTISER_ID            = 500;
    public const ADVERTISER_ID_FOR_UPDATE = 4081;

    public function testGetMeta(): void
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, self::ADVERTISER_ID);
        $advertiserMeta = $advertiser->getAdvertiserMeta();

        isSame($advertiser->id, $advertiserMeta->advertiser_id);
        isEmpty($advertiserMeta->ssn_tax);
        isSame(AdvertiserMeta::DEFAULT_VAT_ID, $advertiserMeta->default_vat_id);
    }

    /**
     * @depends testGetMeta
     */
    public function testCanUpdateMeta(): void
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, self::ADVERTISER_ID_FOR_UPDATE);

        $advertiserMeta = $advertiser->getAdvertiserMeta();
        $advertiserMeta->default_vat_id = AdvertiserMeta::DEFAULT_VAT_ID;
        $newSsnTax = '' . random_int(1, 50);

        isNotSame($newSsnTax, $advertiserMeta->ssn_tax);
        $advertiserMeta->ssn_tax = $newSsnTax;
        $advertiserMeta->save();

        isSame($newSsnTax, $advertiserMeta->ssn_tax);
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Item8\HasOffers\Entity\AdvertiserMeta
     * @throws \Item8\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty(): void
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->hoClient->get(Advertiser::class, self::ADVERTISER_ID);
        $advertiserMeta = $advertiser->getAdvertiserMeta();
        is(self::ADVERTISER_ID, $advertiserMeta->advertiser_id);

        $advertiserMeta->undefined_property;
    }
}
