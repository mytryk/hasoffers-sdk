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
    public function testFindList()
    {
        /** @var AdvertiserInvoices $users */
        $invoices = $this->hoClient->get(AdvertiserInvoices::class);
        /** @var AdvertiserUser $user */
        $condition = [
            'filters' => [
                'id' => 32
            ]
        ];
        $invoice = $invoices->find($condition)[32];

        isSame('32', $invoice->id);
        isSame('502', $invoice->advertiser_id);
    }
}
