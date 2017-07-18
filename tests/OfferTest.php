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

use JBZoo\Utils\Str;
use Unilead\HasOffers\Entity\Offer;

/**
 * Class OfferTest
 * @package JBZoo\PHPUnit
 */
class OfferTest extends HasoffersPHPUnit
{
    public function testCreatingOfferWays()
    {
        $offer1 = $this->hoClient->get(Offer::class); // recommended!
        $offer2 = $this->hoClient->get('Offer');
        $offer3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\Offer');
        $offer4 = new Offer();
        $offer4->setClient($this->hoClient);

        isClass(Offer::class, $offer1);
        isClass(Offer::class, $offer2);
        isClass(Offer::class, $offer3);
        isClass(Offer::class, $offer4);

        isNotSame($offer1, $offer2);
        isNotSame($offer1, $offer3);
    }

    public function testCanGetOfferById()
    {
        $someId = '2';
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, $someId);

        is($someId, $offer->id);
    }

    public function testIsExist()
    {
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, '10000000');
        isFalse($offer->isExist());

        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, '2');
        isTrue($offer->isExist());
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\Offer" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $offer = $this->hoClient->get(Offer::class);
        $offer->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\Offer
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $someId = '2';
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, $someId);
        is($someId, $offer->id);

        $offer->undefined_property;
    }

    public function testCanCreateOffer()
    {
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class);
        $offer->name = Str::random();
        $offer->preview_url = 'http://' . Str::random() . '.com/' . Str::random();
        $offer->offer_url = 'http://' . Str::random() . '.com/' . Str::random();
        $offer->expiration_date = date('Y-m-d H:i:s');
        $offer->save();

        /** @var Offer $offerCheck */
        $offerCheck = $this->hoClient->get(Offer::class, $offer->id);

        isSame($offer->id, $offerCheck->id); // Check is new id bind to object
        isSame($offer->name, $offerCheck->name);
        isSame($offer->preview_url, $offerCheck->preview_url);
        isSame($offer->offer_url, $offerCheck->offer_url);
        isSame($offer->expiration_date, $offerCheck->expiration_date);
        isSame($offer->status, Offer::STATUS_PENDING);
    }

    public function testCanUpdateOffer()
    {
        $this->skipIfFakeServer();

        /** @var Offer $offerBeforeSave */
        $offerBeforeSave = $this->hoClient->get(Offer::class, 2);

        $beforeCompany = $offerBeforeSave->name;
        $offerBeforeSave->name = Str::random();
        $offerBeforeSave->save();

        /** @var Offer $offerAfterSave */
        $offerAfterSave = $this->hoClient->get(Offer::class, 2);
        isNotSame($beforeCompany, $offerAfterSave->name);
    }

    public function testCanDeleteOffer()
    {
        $this->skipIfFakeServer();

        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, 2);
        $offer->delete();

        /** @var Offer $offerAfterSave */
        $offerAfterSave = $this->hoClient->get(Offer::class, 2);

        isSame(Offer::STATUS_DELETED, $offerAfterSave->status);
    }

    public function testUpdateOnlyChangedFields()
    {
        $randomValue = Str::random();

        $offer = $this->hoClient->get(Offer::class, 2);
        $offer->name = $randomValue;
        $offer->description = $randomValue;

        isSame([
            'name'        => $randomValue,
            'description' => $randomValue,
        ], $offer->getChangedFields());

        $offer->save();

        isSame([], $offer->getChangedFields());
    }

    public function testNoDataToUpdateIsNotError()
    {
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, 2);
        $offer->save();

        is(2, $offer->id);
    }
}
