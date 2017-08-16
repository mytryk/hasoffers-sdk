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
use JBZoo\Utils\Email;
use JBZoo\Utils\Str;
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\Contain\PaymentMethod;
use Unilead\HasOffers\Entity\AffiliateUser;

/**
 * Class HoClientTest
 *
 * @package JBZoo\PHPUnit
 */
class HoClientTest extends HasoffersPHPUnit
{
    public function testEventManagerAttach()
    {
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);

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

        isSame('No data to create new object "Unilead\HasOffers\Entity\Affiliate" in HasOffers', $checkedMessage);
    }

    public function testLimitOption()
    {
        $this->hoClient->setTimeout(5);
        $this->hoClient->setRequestsLimit(2);

        $startTime = time();
        $affiliate = $this->hoClient->get(Affiliate::class, 1004);
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
}
