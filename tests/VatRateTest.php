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
use Unilead\HasOffers\Entity\VatRate;

/**
 * Class EmployeeTest
 *
 * @package JBZoo\PHPUnit
 */
class VatRateTest extends HasoffersPHPUnit
{
    public const EXISTED_VAT_RATE_ID = 2;

    /**
     * @throws \Unilead\HasOffers\Exception
     */
    public function testCreatingWays(): void
    {
        $this->markTestSkipped('Write me');

        $vatRate1 = $this->hoClient->get(VatRate::class); // recommended!
        $vatRate2 = $this->hoClient->get('VatRate');
        $vatRate3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\VatRate');
        $vatRate4 = new VatRate();
        $vatRate4->setClient($this->hoClient);

        isClass(VatRate::class, $vatRate1);
        isClass(VatRate::class, $vatRate2);
        isClass(VatRate::class, $vatRate3);
        isClass(VatRate::class, $vatRate4);

        isNotSame($vatRate1, $vatRate2);
        isNotSame($vatRate1, $vatRate3);
    }

    /**
     * @throws \Unilead\HasOffers\Exception
     */
    public function testCanGetById(): void
    {
        $this->markTestSkipped('Write me');

        /** @var VatRate $vatRate */
        $vatRate = $this->hoClient->get(VatRate::class, self::EXISTED_VAT_RATE_ID);

        is(self::EXISTED_VAT_RATE_ID, $vatRate->id);
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\VatRate" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId(): void
    {
        $this->markTestSkipped('Write me');

        $vatRate = $this->hoClient->get(VatRate::class);
        $vatRate->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\VatRate
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty(): void
    {
        $this->markTestSkipped('Write me');

        /** @var VatRate $vatRate */
        $vatRate = $this->hoClient->get(VatRate::class, self::EXISTED_VAT_RATE_ID);
        is(self::EXISTED_VAT_RATE_ID, $vatRate->id);

        $vatRate->undefined_property;
    }

    /**
     * @throws \Unilead\HasOffers\Entity\Exception
     * @throws \Unilead\HasOffers\Exception
     * @throws \Exception
     */
    public function testCanCreate(): void
    {
        $this->markTestSkipped('Write me');

        /** @var VatRate $vatRate */
        $vatRate = $this->hoClient->get(VatRate::class);
        $vatRate->name = Str::random(10);
        $vatRate->code = Str::random(5);
        $vatRate->rate = random_int(1, 20) / 10;
        $vatRate->save();

        /** @var VatRate $vatRateCheck */
        $vatRateCheck = $this->hoClient->get(VatRate::class, $vatRate->id);

        isSame($vatRate->id, $vatRateCheck->id); // Check is new id bind to object
        isSame($vatRate->name, $vatRateCheck->name);
        isSame($vatRate->code, $vatRateCheck->code);
        isSame((float)$vatRate->rate, (float)$vatRateCheck->rate);

        $vatRate->delete();
    }

    /**
     * @throws \Unilead\HasOffers\Entity\Exception
     * @throws \Unilead\HasOffers\Exception
     */
    public function testCanUpdate(): void
    {
        $this->markTestSkipped('Write me');

        /** @var VatRate $vatRateBeforeSave */
        $vatRateBeforeSave = $this->hoClient->get(VatRate::class, self::EXISTED_VAT_RATE_ID);

        $beforeName = $vatRateBeforeSave->name;
        $vatRateBeforeSave->name = Str::random();

        $vatRateBeforeSave->save();

        /** @var VatRate $vatRateAfterSave */
        $vatRateAfterSave = $this->hoClient->get(VatRate::class, self::EXISTED_VAT_RATE_ID);
        isNotSame($beforeName, $vatRateAfterSave->name);
    }

    /**
     * @throws \Unilead\HasOffers\Entity\Exception
     * @throws \Unilead\HasOffers\Exception
     * @throws \Exception
     */
    public function testCanDelete(): void
    {
        $this->markTestSkipped('Write me');

        /** @var VatRate $vatRate */
        $vatRate = $this->hoClient->get(VatRate::class);
        $vatRate->name = Str::random(10);
        $vatRate->code = Str::random(5);
        $vatRate->rate = random_int(1, 20) / 10;
        $vatRate->save();

        $createdId = $vatRate->id;
        isNotNull($createdId);

        $vatRate->delete();

        /** @var VatRate $vatRateAfterDelete */
        $vatRateAfterDelete = $this->hoClient->get(VatRate::class, $createdId);
        isNull($vatRateAfterDelete->id);
    }
}
