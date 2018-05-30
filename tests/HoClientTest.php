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

use JBZoo\Data\Data;
use JBZoo\Event\EventManager;
use JBZoo\Utils\Email;
use JBZoo\Utils\Str;
use Item8\HasOffers\Entity\Affiliate;
use Item8\HasOffers\Contain\PaymentMethod;
use Item8\HasOffers\Entity\AffiliateUser;

/**
 * Class HoClientTest
 *
 * @package JBZoo\PHPUnit
 */
class HoClientTest extends HasoffersPHPUnit
{
    protected $testId = 2;

    public function testEventManagerAttach()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);

        $checkerCounter = 0;
        $this->eManager->on('ho.Affiliate.reload.*', function () use (&$checkerCounter) {
            $checkerCounter++;
        });

        $affiliate->reload();

        isSame(2, $checkerCounter);
    }

    public function testEventManagerExceptions()
    {
        $checkedMessage = '';
        $this->eManager->on('ho.exception', function (\Exception $exception) use (&$checkedMessage) {
            $checkedMessage = $exception->getMessage();
        });

        try {
            $affiliate = $this->hoClient->get(Affiliate::class);
            $affiliate->save();
        } catch (\Exception $exception) {
            // noop
        }

        isSame('No data to create new object "Item8\HasOffers\Entity\Affiliate" in HasOffers', $checkedMessage);
    }

    public function testLimitOption()
    {
        $this->hoClient->setTimeout(5);
        $this->hoClient->setRequestsLimit(2);

        $startTime = time();
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $affiliate->reload();
        $affiliate->reload();
        $affiliate->reload();
        $affiliate->reload();
        $finishTime = time();

        isTrue($finishTime - $startTime > 9, 'Timeout is ' . ($finishTime - $startTime));
    }

    public function testFullResponse()
    {
        $customRequest = [
            'Target' => 'Preference',
            'Method' => 'findAll',
        ];

        $response = $this->hoClient->apiRequest($customRequest);
        $responseFull = $this->hoClient->apiRequest($customRequest, false);

        isSame($response->getArrayCopy(), $responseFull->find('response.data', null, 'data')->getArrayCopy());
    }

    public function testLastRequestResponse()
    {
        $customRequest = [
            'Target' => 'Preference',
            'Method' => 'findAll',
        ];

        $response = $this->hoClient->apiRequest($customRequest);

        isSame($customRequest, $this->hoClient->getLastRequest()->getArrayCopy());

        isNotEmpty($response->find('0.Preference.name'));
        isSame(
            $response->find('0.Preference.name'),
            $this->hoClient->getLastResponse()->find('response.data.0.Preference.name')
        );
    }

    /**
     * @expectedException \Item8\HasOffers\Request\Exception
     */
    public function testUndefinedTarget()
    {
        $this->hoClient->apiRequest([
            'Target' => 'Undefined',
            'Method' => 'findAll',
        ]);
    }

    /**
     * @expectedException \Item8\HasOffers\Request\Exception
     */
    public function testUndefinedMethod()
    {
        $this->hoClient->apiRequest([
            'Target' => 'Preference',
            'Method' => 'Undefined',
        ]);
    }

    /**
     * @expectedException \Item8\HasOffers\Request\Exception
     */
    public function testHasOffersError()
    {
        /** @var Affiliate $affiliate */
        $affiliate = $this->hoClient->get(Affiliate::class, $this->testId);
        $paymentMethod = $affiliate->getPaymentMethod();
        $paymentMethod->setType('unit-testing');
    }

    /**
     * @expectedException \Item8\HasOffers\Request\Exception
     */
    public function testUndefinedClass()
    {
        $this->hoClient->get('Undefined');
    }
}
