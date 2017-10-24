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
 *
 * @package JBZoo\PHPUnit
 */
class OfferTest extends HasoffersPHPUnit
{
    protected $testId = '2';

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
        $someId = '4';
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, $someId);

        is($someId, $offer->id);
        is('Beasts of Dungeons (iOS)', $offer->name);
        is('active', $offer->status);

        isSame('iOS', $offer->getRuleTargeting()[0]['Name']);
        isSame('iOS operating system', $offer->getRuleTargeting()[0]['Description']);
        isSame('iOS', $offer->getRuleTargeting()[0]['Platform']);
        isSame('Tutorial', $offer->getDefaultGoal());
        isSame('Other', $offer->getTrackingSystem());

        isTrue($offer->getGoal()->data()->getArrayCopy());
        $goals = $offer->getGoal()->data()->getArrayCopy();
        isSame(1, count($goals));
        isSame('2', $goals[0]['id']);
        isSame('Install', $goals[0]['name']);
        isSame('1.00000', $goals[0]['default_payout']);
        isSame('2.00000', $goals[0]['max_payout']);

        isSame('RU;US', $offer->getCountriesCodes());
    }

    public function testIsExist()
    {
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, '10000000');
        isFalse($offer->isExist());

        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, $this->testId);
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
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, $this->testId);
        is($this->testId, $offer->id);

        $offer->undefined_property;
    }

    public function testCanCreateOffer()
    {
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class);
        $offer->name = $this->faker->company;
        $offer->preview_url = $this->faker->url;
        $offer->offer_url = $this->faker->url;
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

        $offer->delete(); // Clean up after test
    }

    public function testCanUpdateOffer()
    {
        $this->skipIfFakeServer();

        /** @var Offer $offerBeforeSave */
        $offerBeforeSave = $this->hoClient->get(Offer::class, $this->testId);

        $beforeCompany = $offerBeforeSave->name;
        $offerBeforeSave->name = $this->faker->name;
        $offerBeforeSave->save();

        /** @var Offer $offerAfterSave */
        $offerAfterSave = $this->hoClient->get(Offer::class, $this->testId);
        isNotSame($beforeCompany, $offerAfterSave->name);
    }

    public function testCanDeleteOffer()
    {
        $this->skipIfFakeServer();

        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, $this->testId);
        $offer->delete();

        /** @var Offer $offerAfterSave */
        $offerAfterSave = $this->hoClient->get(Offer::class, $this->testId);

        isSame(Offer::STATUS_DELETED, $offerAfterSave->status);
    }

    public function testUpdateOnlyChangedFields()
    {
        $name = $this->faker->name();
        $description = $this->faker->name();

        $offer = $this->hoClient->get(Offer::class, $this->testId);
        $offer->name = $name;
        $offer->description = $description;

        isSame([
            'name'        => $name,
            'description' => $description,
        ], $offer->getChangedFields());

        $offer->save();

        isSame([], $offer->getChangedFields());
    }

    public function testNoDataToUpdateIsNotError()
    {
        /** @var Offer $offer */
        $offer = $this->hoClient->get(Offer::class, $this->testId);
        $offer->save();

        is($this->testId, $offer->id);
    }
}
