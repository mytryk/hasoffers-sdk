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

use Unilead\HasOffers\Entities\AdvertiserInvoices;
use Unilead\HasOffers\Entity\AdvertiserUser;

/**
 * Class AdvertiserInvoicesTest
 *
 * @package JBZoo\PHPUnit
 */
class AdvertiserInvoicesTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testFindList()
    {
        /** @var AdvertiserInvoices $users */
        $invoices = $this->hoClient->get(AdvertiserInvoices::class);
        /** @var AdvertiserUser $user */
        $condition = [
            'filters' => [
                'id' => $this->testId,
            ],
        ];
        $invoice = $invoices->find($condition)[$this->testId];

        isSame($this->testId, $invoice->id);
        isSame('500', $invoice->advertiser_id);
    }
}
