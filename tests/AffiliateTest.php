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

use JBZoo\Data\Data;
use JBZoo\Event\EventManager;
use JBZoo\Utils\Env;
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\HasOffersClient;
use Unilead\HasOffers\PaymentMethod;

/**
 * Class AffiliateTest
 * @package JBZoo\PHPUnit
 */
class AffiliateTest extends PHPUnit
{
    /**
     * @var HasOffersClient
     */
    protected $hoClient;

    public function setUp()
    {
        parent::setUp();

        $this->hoClient = new HasOffersClient(
            Env::get('HO_API_NETWORK_ID'),
            Env::get('HO_API_NETWORK_TOKEN')
        );
    }

    public function testEventManagerAttach()
    {
        $eManager = new EventManager();
        $this->hoClient->setEventManager($eManager);

        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);

        $checkerCounter = 0;
        $eManager->on('ho.*.reload.*', function () use (&$checkerCounter) {
            $checkerCounter++;
        });

        $affiliate->reload();

        isSame(2, $checkerCounter);
    }

    public function testLimitOption()
    {
        $this->hoClient->setTimeout(5);
        $this->hoClient->setRequestsLimit(2);

        $startTime = time();
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->reload();
        $affiliate->reload();
        $affiliate->reload();
        $affiliate->reload();
        $finishTime = time();

        isTrue($finishTime - $startTime > 10, 'Timeout is ' . ($finishTime - $startTime));
    }

    public function testCreatingAffiliateWays()
    {
        $affiliate1 = $this->hoClient->get(Affiliate::class); // recomended!
        $affiliate2 = $this->hoClient->get('Affiliate');
        $affiliate3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\Affiliate');
        $affiliate4 = new Affiliate();
        $affiliate4->setClient($this->hoClient);

        isClass(Affiliate::class, $affiliate1);
        isClass(Affiliate::class, $affiliate2);
        isClass(Affiliate::class, $affiliate3);
        isClass(Affiliate::class, $affiliate4);

        isNotSame($affiliate1, $affiliate2);
        isNotSame($affiliate1, $affiliate3);
    }

    public function testCanGetAffiliateById()
    {
        $someId = '1004';
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);

        is($someId, $affiliate->id);
    }

    /**
     * @expectedExceptionMessage Missing required argument: data
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\Affiliate
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $someId = '1004';
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        is($someId, $affiliate->id);

        $affiliate->undefined_property;
    }

    public function testGetAffiliatePaymentMethodType()
    {
        $someId = '1004';
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, $someId);
        $paymentMethod = $affiliate->getPaymentMethod();

        isSame(PaymentMethod::TYPE_PAYPAL, $paymentMethod->getType());

        $paymentRawData = $paymentMethod->getRawData();
        isClass(Data::class, $paymentRawData);
        isSame('abelov83@belov.ru', $paymentRawData->email);
        isSame('abelov83@belov.ru', $paymentMethod->email);
    }

    public function testCanCreateAffiliate()
    {
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class);
        $affiliate->company = 'Test Company';
        $affiliate->phone = '+7 845 845 84 54';
        $affiliate->save();

        /** @var Affiliate $affiliateCheck */
        $affiliateCheck = $this->hoClient->get(Affiliate::class, $affiliate->id);

        isSame($affiliate->id, $affiliateCheck->id);
        isSame($affiliate->company, $affiliateCheck->company);
        isSame($affiliate->phone, $affiliateCheck->phone);
    }

    public function testCanUpdateAffiliate()
    {
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class);

        $affiliate->id = 1004;
        $affiliate->company = 'Test Company';
        $affiliate->phone = '+7 845 845 84 54';
        $affiliate->status = Affiliate::STATUS_ACTIVE;
        $affiliate->save();

        /** @var Affiliate $affiliateCheck */
        $affiliateCheck = $this->hoClient->get(Affiliate::class, $affiliate->id);

        isSame($affiliate->id, $affiliateCheck->id);
        isSame($affiliate->company, $affiliateCheck->company);
        isSame($affiliate->phone, $affiliateCheck->phone);
    }

    public function testCanDeleteAffiliate()
    {
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
        $affiliate->delete();

        isSame(Affiliate::STATUS_DELETED, $affiliate->status);
    }
}
